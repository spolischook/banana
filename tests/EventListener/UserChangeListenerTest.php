<?php

namespace App\Tests\EventListener;

use App\Entity\User;
use App\Entity\UserFollowEvent;
use App\Entity\UserTypeEvent;
use App\Entity\UserUnfollowEvent;
use App\Tests\Ig\CleanDb;
use Doctrine\ORM\EntityManager;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserChangeListenerTest extends CleanDb
{
    public function testOnFlush()
    {
        $this->loadFixtures(__DIR__.'/fixtures/users.yaml');
        /** @var User $user */
        $user = $this->getEm()->getRepository(User::class)->findOneBy([]);

        $this->checkUserTypeEvent($user);
        $this->checkFollowEvent($user);
    }

    protected function getClass()
    {
        return User::class;
    }

    private function checkFollowEvent(User $user)
    {
        $followEventRepository = $this->getEm()->getRepository(UserFollowEvent::class);
        $unfollowEventRepository = $this->getEm()->getRepository(UserUnfollowEvent::class);

        self::assertCount(0, $followEventRepository->findAll());
        self::assertCount(0, $unfollowEventRepository->findAll());
        $user->setIsFollower(true);

        $this->getEm()->flush();

        /** @var UserFollowEvent $userFollowEvent */
        $userFollowEvent = $followEventRepository->findOneBy([]);

        self::assertNotNull($userFollowEvent);
        self::assertEquals($user->getPk(), $userFollowEvent->getUser()->getPk());

        // Unfollow event still null
        self::assertCount(0, $unfollowEventRepository->findAll());

        $user->setIsFollower(false);
        $this->getEm()->flush();

        self::assertCount(1, $followEventRepository->findAll());
        /** @var UserUnfollowEvent $userUnfollowEvent */
        $userUnfollowEvent = $unfollowEventRepository->findOneBy([]);

        self::assertNotNull($userUnfollowEvent);
        self::assertEquals($user->getPk(), $userUnfollowEvent->getUser()->getPk());
    }

    private function checkUserTypeEvent(User $user): void
    {
        self::assertNull($this->getEm()->getRepository(UserTypeEvent::class)->findOneBy([]));
        $user->setUserType(User::INTERESTING_USER);

        $this->getEm()->flush();

        /** @var UserTypeEvent $userTypeEvent */
        $userTypeEvent = $this->getEm()->getRepository(UserTypeEvent::class)->findOneBy([]);

        self::assertNotNull($userTypeEvent);
        self::assertEquals(User::INTERESTING_USER, $userTypeEvent->getType());
        self::assertEquals($user->getPk(), $userTypeEvent->getUser()->getPk());
    }


}
