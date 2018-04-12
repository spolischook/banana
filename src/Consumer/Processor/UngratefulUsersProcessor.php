<?php

namespace App\Consumer\Processor;

use App\Consumer\Processor\Message\MessageInterface;
use App\Consumer\Processor\Message\UngratefulUsersMessage;
use App\Consumer\Processor\Message\UntouchUserMessage;
use App\Entity\User;
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
//        var_dump(count($ungratefulUsers));
//        return;

        /** @var User $ungratefulUser */
        foreach ($ungratefulUsers as $ungratefulUser) {
            $unTouchMessage = new UntouchUserMessage();
            $unTouchMessage->setUserId($ungratefulUser->getPk());
            $this->logger->info(sprintf(
                'User "https://www.instagram.com/%s" unactive, unsubscribe him',
                $ungratefulUser->getUsername()
            ));
            $this->producer->publish($unTouchMessage);
            $message->decreaseProcessingCount();
            if ($message->getMaxProcessing() <= 0) {
                return;
            }
        }
    }
}
