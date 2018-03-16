<?php

namespace App\Consumer\Processor;

use App\Consumer\Message;

abstract class AbstractProcessor implements MessageProcessorInterface
{
    protected $type;

    public function support(Message $message): bool
    {
        return $message->type === $this->type;
    }

    protected function waitFor($min, $max, $reason) {
        $wait = rand($min, $max);
        $this->logger->info($reason.sprintf(' | Wait for %s seconds', $wait));
        sleep($wait);
    }
}
