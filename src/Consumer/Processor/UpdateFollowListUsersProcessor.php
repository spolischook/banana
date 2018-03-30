<?php

namespace App\Consumer\Processor;

use App\Consumer\Processor\Message\MessageInterface;
use App\Consumer\Processor\Message\UpdateFollowListUsersMessage;
use App\Entity\User;
use App\Ig\IgSingleton;
use App\Ig\UserManager;
use App\Producer;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use InstagramAPI\Instagram;
use Psr\Log\LoggerInterface;

class UpdateFollowListUsersProcessor extends AbstractProcessor
{
    protected $logger;
    /** @var Instagram */
    protected $ig;
    protected $producer;
    /** @var EntityManager */
    protected $em;
    protected $userManager;

    public function __construct(
        LoggerInterface $logger,
        IgSingleton $igSingleton,
        Producer $producer,
        ObjectManager $em,
        UserManager $userManager
    ) {
        $this->logger = $logger;
        $this->ig = $igSingleton->getIg();
        $this->producer = $producer;
        $this->em = $em;
        $this->userManager = $userManager;
    }

    protected function getSupportedMessages(): array
    {
        return [UpdateFollowListUsersMessage::class];
    }

    /**
     * @param MessageInterface|UpdateFollowListUsersMessage $message
     */
    public function process(MessageInterface $message): void
    {
        $this->logger->warning('Get following list');
        $me = $this->ig->account->getCurrentUser()->getUser();

        $response = $this->ig->people->getFollowing(
            $me->getPk(),
            $message->getRankToken(),
            null,
            $message->getMaxId()
        );

        foreach ($response->getUsers() as $user) {
            $user = $this->userManager->updateOrCreate($user->asJson());
            $user->setIFollow(true);
            $message->addUser($user->getPk());
        }

        $this->em->flush();

        $nextMessage = clone $message;
        $nextMessage->setMaxId($response->getNextMaxId());

        if (null !== $nextMessage->getMaxId()) {
            $this->producer->publish($nextMessage);
        } else {
            $this->markUnfollowingUsers($message);
            $this->logger->info('Last page of followers reached, exit');
            return;
        }

        $this->waitFor(10, 20, 'Wait for next page of followers');
    }

    /**
     * @param UpdateFollowListUsersMessage $message
     */
    private function markUnfollowingUsers(UpdateFollowListUsersMessage $message): void
    {
        $following = $this->em->getRepository(User::class)->findBy(['iFollow' => true]);
        $followingIds = array_map(function (User $user) {
            return $user->getPk();
        }, $following);

        $unfollowingUsers = array_diff($followingIds, $message->getUsers());

        foreach ($unfollowingUsers as $unfollowingUserId) {
            $unUser = $this->em->getRepository(User::class)->find($unfollowingUserId);
            $unUser->setIFollow(false);
            $this->logger->warning(sprintf(
                'You are unsubscribe from "https://www.instagram.com/%s"',
                $unUser->getUsername()
            ));
        }

        $this->em->flush();
    }
}
