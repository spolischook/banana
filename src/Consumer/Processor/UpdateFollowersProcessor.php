<?php

namespace App\Consumer\Processor;

use App\Consumer\Processor\Message\MessageInterface;
use App\Consumer\Processor\Message\UpdateFollowersMessage;
use App\Entity\User;
use App\Ig\IgSingleton;
use App\Ig\UserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use InstagramAPI\Instagram;
use App\Producer;
use Psr\Log\LoggerInterface;

class UpdateFollowersProcessor extends AbstractProcessor
{
    protected $logger;
    /** @var Instagram */
    protected $ig;
    protected $producer;
    /** @var EntityManager */
    protected $em;
    protected $userManager;

    public function __construct(
        LoggerInterface $logger,
        IgSingleton $igSingleton,
        Producer $producer,
        ObjectManager $em,
        UserManager $userManager
    ) {
        $this->logger = $logger;
        $this->ig = $igSingleton->getIg();
        $this->producer = $producer;
        $this->em = $em;
        $this->userManager = $userManager;
    }

    /**
     * @param MessageInterface|UpdateFollowersMessage $message
     */
    public function process(MessageInterface $message): void
    {
        $this->logger->warning('Update the my followers');
        $me = $this->ig->account->getCurrentUser()->getUser();

        $followersResponse = $this->ig->people->getFollowers(
            $me->getPk(),
            $message->getRankToken(),
            null,
            $message->getMaxId()
        );

        foreach ($followersResponse->getUsers() as $user) {
            $user = $this->userManager->updateOrCreate($user->asJson());
            $user->setIsFollower(true);
            $message->addFollower($user->getPk());
        }

        $this->em->flush();

        $nextMessage = clone $message;
        $nextMessage->setMaxId($followersResponse->getNextMaxId());

        if (null !== $nextMessage->getMaxId()) {
            $this->producer->publish($nextMessage);
        } else {
            $this->markUnsubscribedUsers($message);
            $this->logger->info('Last page of followers reached, exit');
            return;
        }

        $this->waitFor(10, 20, 'Wait for next page of followers');
    }

    protected function getSupportedMessages(): array
    {
        return [UpdateFollowersMessage::class];
    }

    /**
     * @param UpdateFollowersMessage $message
     */
    private function markUnsubscribedUsers(UpdateFollowersMessage $message): void
    {
        $followers = $this->em->getRepository(User::class)->findBy(['isFollower' => true]);
        $followerIds = array_map(function (User $user) {
            return $user->getPk();
        }, $followers);

        $unsubscribeUsers = array_diff($followerIds, $message->getFollowers());

        foreach ($unsubscribeUsers as $unsubscribeUserId) {
            $unUser = $this->em->getRepository(User::class)->find($unsubscribeUserId);
            $unUser->setIsFollower(false);
            $this->logger->warning(sprintf(
                'User "https://www.instagram.com/%s" was unsubscribe',
                $unUser->getUsername()
            ));
        }

        $this->em->flush();
    }
}
