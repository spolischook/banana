<?php

namespace App\Consumer\Processor;

use App\Consumer\Processor\Message\MessageInterface;

interface MessageProcessorInterface
{
    /**
     * @param MessageInterface $message
     */
    public function process(MessageInterface $message): void;

    public function support(MessageInterface $message): bool;
}
