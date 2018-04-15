<?php

namespace App\Consumer\Processor;

use App\Consumer\Message;
use App\Consumer\Processor\Message\AbstractPaginationMessage;
use App\Consumer\Processor\Message\DiscoverPeopleByPlaceMessage;
use App\Consumer\Processor\Message\DiscoverPeopleByTagMessage;
use App\Consumer\Processor\Message\FeedIdInterface;
use App\Consumer\Processor\Message\MessageInterface;
use App\Entity\User;
use App\Ig\IgSingleton;
use App\Ig\ItemManager;
use App\Ig\UserManager;
use App\Producer;
use InstagramAPI\Signatures;
use Psr\Log\LoggerInterface;

class DiscoverPeopleProcessor extends AbstractProcessor
{
    const MIN_MEDIA_COUNT = 10;
    const MAX_FOLLOWER_COUNT = 10000;
    const MAX_FOLLOWING_COUNT = 3000;
    const MIN_FOLLOW_BACK_RATIO = 0.05;

    protected $logger;
    protected $uuid;
    protected $ig;
    protected $userManager;
    protected $itemManager;
    protected $producer;

    public function __construct(
        LoggerInterface $logger,
        IgSingleton $igSingleton,
        UserManager $userManager,
        ItemManager $itemManager,
        Producer $producer
    ) {
        $this->logger = $logger;
        $this->ig = $igSingleton->getIg();
        $this->userManager = $userManager;
        $this->itemManager = $itemManager;
        $this->producer = $producer;
        $this->uuid = Signatures::generateUUID();
    }

    /**
     * @param AbstractPaginationMessage|FeedIdInterface $message
     */
    public function process(MessageInterface $message): void
    {
        $this->logger->warning(sprintf(
            'Get page of "%s" by id "%s"',
            $message->getPageNumber() !== null ? $message->getPageNumber() : 'infinity',
            $message->getFeedId()
        ));
        $response = $this->getFeedSource($message)->getFeed($message->getFeedId(), $this->uuid, $message->getMaxId());
        foreach ($response->getItems() as $item) {
            $user = $item->getUser();
            $jsonUser = $this->ig->people->getInfoByName($user->getUsername())->asJson();
            $this->logger->info(sprintf('Save "%s" user', $user->getUsername()));
            $user = $this->userManager->updateOrCreate($jsonUser);

            if ($user->getUserType() !== null) {
                $this->logger->error('User already discovered, skipping it');
                continue;
            }

            if ($user->isFollower()) {
                $this->logger->error('User already follower');
                continue;
            }

            if ($user->isIFollow()) {
                $this->logger->error('User already followed');
                continue;
            }

            if (self::MIN_MEDIA_COUNT > $user->getMediaCount()) {
                $this->logger->error('User has too few (10<) posts');
                continue;
            }

            if (self::MAX_FOLLOWER_COUNT < $user->getFollowerCount()) {
                $this->logger->error('User has too much (> 10000) followers');
                continue;
            }

            if (self::MAX_FOLLOWING_COUNT < $user->getFollowingCount()) {
                $this->logger->error('User too much following (>2000), it\'s a robot?');
                continue;
            }

            if (
                0 === $user->getFollowerCount() ||
                self::MIN_FOLLOW_BACK_RATIO > $user->getFollowingCount()/$user->getFollowerCount()
            ) {
                $this->logger->error('User has too low followback rate');
                continue;
            }

            $user->setUserType(User::FOUND);
            $this->logger->info('Save users timeline feed');
            $feed = $this->ig->timeline->getUserFeed($user->getPk());

            foreach ($feed->getItems() as $userItem) {
                $this->itemManager->updateOrCreate($userItem->asJson());
                $this->logger->info('Saved user item');
            }
        }

        $maxId = $response->getNextMaxId();

        if (null === $maxId) {
            $this->logger->info('This is the last page of response, exit');
            return;
        }

        $nextMessage = clone $message;
        $nextMessage
            ->decreasePageNumber()
            ->setMaxId($maxId);

        if (0 !== $nextMessage->getPageNumber()) {
            $this->producer->publish($nextMessage);
        }

        $this->waitFor(5, 10, 'Wait after tag discover page');
    }

    protected function getFeedSource(MessageInterface $message)
    {
        switch (get_class($message)) {
            case DiscoverPeopleByTagMessage::class:
                return $this->ig->hashtag;
            case DiscoverPeopleByPlaceMessage::class:
                return $this->ig->location;
            default:
                throw new \InvalidArgumentException(sprintf(
                    'Class "%s" is not supported by "%s"',
                    get_class($message),
                    get_class($this)
                ));
        }
    }

    protected function getSupportedMessages(): array
    {
        return [
            DiscoverPeopleByTagMessage::class,
            DiscoverPeopleByPlaceMessage::class,
        ];
    }
}
