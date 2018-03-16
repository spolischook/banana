<?php

namespace App\Consumer\Processor;

use App\Consumer\Message;
use App\Ig\IgSingleton;
use InstagramAPI\Instagram;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Psr\Log\LoggerInterface;

class UpdateMyFeedProcessor extends AbstractProcessor
{
    protected $type = 'update_my_feed';
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

    public function process(Message $message): void
    {
        $this->logger->warning('Update the my feed');
        $maxId = $message->data['maxId'];

        $response = $this->ig->timeline->getSelfUserFeed($maxId);
        foreach ($response->getItems() as $item) {
            $message = new Message('update_my_item');
            $message->data = ['itemId' => $item->getId()];
            $this->producer->publish(json_encode($message));
        }

        $maxId = $response->getNextMaxId();

        if (null === $maxId) {
            $this->logger->info('There is a last page of the feed, exit');
            return;
        }

        $message = new Message($this->type);
        $message->data = ['maxId' => $maxId];
        $this->producer->publish(json_encode($message));

        $this->waitFor(5, 10, 'Wait after update feed');
    }
}
