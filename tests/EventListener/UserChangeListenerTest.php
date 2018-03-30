<?php

namespace App\Tests\EventListener;

use App\Entity\User;
use App\Entity\UserFollowEvent;
use App\Entity\UserTypeEvent;
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
        self::assertNull($this->getEm()->getRepository(UserFollowEvent::class)->findOneBy([]));
        $user->setIsFollower(true);

        $this->getEm()->flush();

        /** @var UserFollowEvent $userFollowEvent */
        $userFollowEvent = $this->getEm()->getRepository(UserFollowEvent::class)->findOneBy([]);

        self::assertNotNull($userFollowEvent);
        self::assertTrue($userFollowEvent->getFollowingStatus());
        self::assertEquals($user->getPk(), $userFollowEvent->getUser()->getPk());
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
