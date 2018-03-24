<?php

namespace App\Consumer\Processor;

use App\Consumer\Processor\Message\MessageInterface;
use App\Consumer\Processor\Message\UpdateItemMessage;
use App\Consumer\Processor\Message\UpdateMyFeedMessage;
use App\Ig\IgSingleton;
use InstagramAPI\Instagram;
use App\Producer;
use Psr\Log\LoggerInterface;

class UpdateMyFeedProcessor extends AbstractProcessor
{
    protected $logger;
    /** @var Instagram */
    protected $ig;
    protected $producer;

    public function __construct(
        LoggerInterface $logger,
        IgSingleton $igSingleton,
        Producer $producer
    ) {
        $this->logger = $logger;
        $this->ig = $igSingleton->getIg();
        $this->producer = $producer;
    }

    /**
     * @param MessageInterface|UpdateMyFeedMessage $message
     */
    public function process(MessageInterface $message): void
    {
        $this->logger->warning(sprintf(
            'Update "%s" page of my feed',
            $message->getPageNumber() === null ? 'infinity' : $message->getPageNumber()
        ));
        $maxId = $message->getMaxId();

        $response = $this->ig->timeline->getSelfUserFeed($maxId);
        foreach ($response->getItems() as $item) {
            $updateItemMessage = new UpdateItemMessage();
            $updateItemMessage->setItemId($item->getId());
            $this->producer->publish($updateItemMessage);
        }

        $maxId = $response->getNextMaxId();

        if (null === $maxId) {
            $this->logger->info('There is a last page of the feed, exit');
            return;
        }

        $nextMessage = new UpdateMyFeedMessage();
        $nextMessage
            ->setMaxId($maxId)
            ->setPageNumber($message->getPageNumber())
            ->decreasePageNumber();

        if (0 !== $message->getPageNumber()) {
            $this->producer->publish($nextMessage);
        }

        $this->waitFor(5, 10, 'Wait after update feed');
    }

    protected function getSupportedMessages(): array
    {
        return [UpdateMyFeedMessage::class];
    }
}
