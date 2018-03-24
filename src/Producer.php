<?php

namespace App;

use App\Consumer\Processor\Message\MessageInterface;
use OldSound\RabbitMqBundle\RabbitMq\Producer as BaseProducer;

class Producer extends BaseProducer
{
    /**
     * {@inheritdoc}
     */
    public function publish(
        $message,
        $routingKey = '',
        $additionalProperties = [],
        array $headers = null
    ) {
        if ($message instanceof MessageInterface) {
            $message = json_encode(serialize($message));
        }

        parent::publish($message, $routingKey, $additionalProperties, $headers);
    }
}
