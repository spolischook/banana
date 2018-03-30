<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Tests\Ig\CleanDb;

class UserRepositoryTest extends CleanDb
{
    public function testFindUnrequitedUsers()
    {
        $this->loadFixtures(__DIR__.'/fixtures/users.yaml');
        /** @var User[] $users */
        $users = $this->getEm()->getRepository(User::class)->findUngratefulUsers();
        self::assertCount(1, $users);
        self::assertNotCount(0, $users[0]->getEvents());

        $users = $this->getEm()->getRepository(User::class)->findUngratefulUsers(60);
        self::assertCount(0, $users);

        $users = $this->getEm()->getRepository(User::class)->findUngratefulUsers(10);
        self::assertCount(2, $users);
    }

    protected function getClass()
    {
        return User::class;
    }
}
