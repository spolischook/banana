<?php

namespace App\Consumer\Processor;

use App\Consumer\Processor\Message\MessageInterface;
use App\Consumer\Processor\Message\UngratefulUsersMessage;
use App\Consumer\Processor\Message\UntouchUserMessage;
use App\Producer;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;

class UngratefulUsersProcessor extends AbstractProcessor
{
    protected $logger;
    protected $userRepository;
    protected $producer;

    public function __construct(
        LoggerInterface $logger,
        UserRepository $userRepository,
        Producer $producer
    ) {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->producer = $producer;
    }

    protected function getSupportedMessages(): array
    {
        return [UngratefulUsersMessage::class];
    }

    /**
     * @param MessageInterface|UngratefulUsersMessage $message
     */
    public function process(MessageInterface $message): void
    {
        $ungratefulUsers = $this->userRepository->findUngratefulUsers($message->getUnActiveDays());
        var_dump(count($ungratefulUsers)); return;
        $count = 300;

        foreach ($ungratefulUsers as $ungratefulUser) {
            $message = new UntouchUserMessage();
            $message->setUserId($ungratefulUser->getPk());
            $this->logger->info(sprintf(
                'User "https://www.instagram.com/%s" unactive, unsubscribe him',
                $ungratefulUser->getUsername()
            ));
//            $this->producer->publish($message);
//            $count--;
            if ($count <= 0) {
                return;
            }
        }
    }
}
