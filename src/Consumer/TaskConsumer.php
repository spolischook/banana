<?php

namespace App\Consumer;

use App\Consumer\Processor\Message\MessageInterface;
use App\Consumer\Processor\MessageProcessorInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Serializer;

class TaskConsumer implements ConsumerInterface
{
    protected $logger;
    protected $serializer;

    /**
     * @var array|MessageProcessorInterface[]
     */
    protected $processors;

    public function __construct(
        LoggerInterface $logger,
        Serializer $serializer,
        iterable $processors
    ) {
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->processors = $processors;
    }

    /**
     * @param AMQPMessage $msg The message
     * @return mixed false to reject and requeue, any other value to acknowledge
     */
    public function execute(AMQPMessage $msg)
    {
        $this->logger->info('Message received: '.$msg->getBody());

        try {
            /** @var MessageInterface $message */
            $message = unserialize(json_decode($msg->getBody()));

            foreach ($this->processors as $processor) {
                if ($processor->support($message)) {
                    $processor->process($message);

                    return true;
                }
            }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());

            return false;
        }

        $this->logger->alert(sprintf('No processor found for message'));
        return true;
    }
}
