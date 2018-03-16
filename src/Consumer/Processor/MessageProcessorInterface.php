<?php

namespace App\Consumer\Processor;

use App\Consumer\Message;

interface MessageProcessorInterface
{
    public function process(Message $message): void;

    public function support(Message $message): bool;
}
