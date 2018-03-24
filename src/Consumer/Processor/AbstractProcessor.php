<?php

namespace App\Consumer\Processor;

use App\Consumer\Processor\Message\MessageInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractProcessor implements MessageProcessorInterface
{
    /** @var LoggerInterface */
    protected $logger;

    public function support(MessageInterface $message): bool
    {
        return in_array(get_class($message), $this->getSupportedMessages());
    }

    protected function waitFor($min, $max, $reason) {
        $wait = rand($min, $max);
        $this->logger->info($reason.sprintf(' | %s seconds', $wait));
        sleep($wait);
    }

    abstract protected function getSupportedMessages(): array;
}
