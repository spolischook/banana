<?php

namespace App\Consumer\Processor;

use App\Consumer\Processor\Message\LikeMyFeedMessage;
use App\Consumer\Processor\Message\MessageInterface;
use App\Ig\IgSingleton;
use App\Producer;
use App\Repository\UserRepository;
use InstagramAPI\Instagram;
use Psr\Log\LoggerInterface;

class LikeMyFeedProcessor extends AbstractProcessor
{
    protected $logger;
    /** @var Instagram */
    protected $ig;
    protected $producer;
    protected $userRepository;

    public function __construct(
        LoggerInterface $logger,
        IgSingleton $igSingleton,
        Producer $producer,
        UserRepository $userRepository
    ) {
        $this->logger = $logger;
        $this->ig = $igSingleton->getIg();
        $this->producer = $producer;
        $this->userRepository = $userRepository;
    }

    protected function getSupportedMessages(): array
    {
        return [LikeMyFeedMessage::class];
    }

    /**
     * @param MessageInterface|LikeMyFeedMessage $message
     */
    public function process(MessageInterface $message): void
    {
        $this->logger->info('Like my feed');
        $response = $this->ig->timeline->getTimelineFeed($message->getMaxId());

        foreach ($response->getFeedItems() as $feedItem) {
//            $feedItem->printJson();
            $item = $feedItem->getMediaOrAd();

            if ($item->isHasLiked()) {
                $this->logger->info(sprintf(
                    'Post "https://www.instagram.com/p/%s" was liked before, skip it',
                    $item->getCode()
                ));
                continue;
            }

            if (!$item->getUser()) {
                $this->logger->info(sprintf(
                    'Post "https://www.instagram.com/p/%s" without user, skip it',
                    $item->getCode()
                ));
                continue;
            }

            $user = $this->userRepository->find($item->getUser()->getPk());

            if (!$user) {
                $this->logger->info(sprintf(
                    'Post "https://www.instagram.com/p/%s" unknown user, skip it',
                    $item->getCode()
                ));
                continue;
            }

            $this->ig->media->like($item->getId());
            $this->logger->info(sprintf(
                'Post "https://www.instagram.com/p/%s" was liked',
                $item->getCode()
            ));
            $this->waitFor(5,10, 'Wait for the next click');
        }

        $nextMessage = clone $message;
        $nextMessage->decreasePageNumber();

        if (0 !== $message->getPageNumber()) {
            $this->producer->publish($nextMessage);
        }

        $this->waitFor(5, 10, 'Wait after like feed');
    }
}
