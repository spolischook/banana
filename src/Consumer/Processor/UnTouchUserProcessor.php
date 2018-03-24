<?php

namespace App\Consumer\Processor;

use App\Consumer\Processor\Message\MessageInterface;

class UnTouchUserProcessor extends AbstractProcessor
{
    protected function getSupportedMessages(): array
    {
        // TODO: Implement getSupportedMessages() method.
    }

    /**
     * @param MessageInterface $message
     */
    public function process(MessageInterface $message): void
    {
        // TODO: Implement process() method.
    }
}
