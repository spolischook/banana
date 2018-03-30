<?php

namespace App\Consumer\Processor;

use App\Consumer\Processor\Message\MessageInterface;
use App\Consumer\Processor\Message\UntouchUserMessage;
use App\Entity\User;
use App\Ig\IgSingleton;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use InstagramAPI\Instagram;
use Psr\Log\LoggerInterface;

class UnTouchUserProcessor extends AbstractProcessor
{
    protected $logger;
    /** @var Instagram */
    protected $ig;
    /** @var EntityManager */
    protected $em;

    public function __construct(
        LoggerInterface $logger,
        IgSingleton $igSingleton,
        ObjectManager $em
    ) {
        $this->logger = $logger;
        $this->ig = $igSingleton->getIg();
        $this->em = $em;
    }

    protected function getSupportedMessages(): array
    {
        return [UntouchUserMessage::class];
    }

    /**
     * @param UntouchUserMessage $message
     */
    public function process(MessageInterface $message): void
    {
        $this->ig->people->unfollow($message->getUserId());
        $this->logger->info(sprintf('User with id "%s" was unfollowed', $message->getUserId()));

        $user = $this->em->find(User::class, $message->getUserId());
        $user->setIFollow(false);
        $this->em->flush();
        $this->logger->info(sprintf('User "%s" was marked in DB as not following', $user->getUsername()));

        $this->waitFor(30, 60, 'Wait before next processor');
    }
}
