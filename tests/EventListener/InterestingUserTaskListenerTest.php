<?php

namespace App\Tests\EventListener;

use App\Entity\User;
use App\Tests\Ig\CleanDb;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InterestingUserTaskListenerTest extends CleanDb
{
    public function testOnFlush()
    {
        $client = self::createClient();
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = new User();
        $user
            ->setPk('123')
            ->setUsername('test_user')
            ->setFullName('Test User')
            ->setIsPrivate(false)
            ->setIsVerified(false)
            ->setProfilePicUrl('')
            ->setProfilePicId('');

        $em->persist($user);
        $em->flush();

        $user->setUserType(User::INTERESTING_USER);
        $em->flush();
    }

    protected function getClass()
    {
        return User::class;
    }
}
