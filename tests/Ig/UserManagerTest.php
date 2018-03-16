<?php

namespace App\Tests\Ig;

use App\Entity\User;
use App\Ig\UserManager;

class UserManagerTest extends CleanDb
{
    public function testCreateUser()
    {
        $json = file_get_contents(__DIR__.'/data/user.json');
        $data = json_decode($json, true);

        $this->assertEntityNotInDb($data['user']['pk']);

        $this->client->getContainer()
            ->get('test.'.UserManager::class)
            ->updateOrCreate($json);

        $this->assertEntityInDb($data['user']['pk']);

        /** @var User $user */
        $user = $this->getEntityFromDb($data['user']['pk']);
        $this->assertEquals('tetianacherevan', $user->getUsername());
    }

    public function testUpdateUser()
    {
        $json = file_get_contents(__DIR__.'/data/user.json');
        $data = json_decode($json, true);

        $this->assertEntityNotInDb($data['user']['pk']);

        $user = new User();
        $user
            ->setPk($data['user']['pk'])
            ->setUsername('test_user')
            ->setFullName('Test User')
            ->setIsPrivate(false)
            ->setIsVerified(false)
            ->setProfilePicUrl('')
            ->setProfilePicId('');

        $this->getEm()->persist($user);
        $this->getEm()->flush();

        /** @var User $user */
        $user = $this->getEntityFromDb($data['user']['pk']);

        $this->assertEquals('test_user', $user->getUsername());
        $this->assertEquals('Test User', $user->getFullName());

        $this->client->getContainer()
            ->get('test.'.UserManager::class)
            ->updateOrCreate($json);

        /** @var User $user */
        $user = $this->getEntityFromDb($data['user']['pk']);

        $this->assertEquals('tetianacherevan', $user->getUsername());
        $this->assertEquals('Tetiana Cherevan', $user->getFullName());
    }

    protected function getClass()
    {
        return User::class;
    }
}
