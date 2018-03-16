<?php

namespace App\Ig;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use InstagramAPI\Instagram;
use JMS\Serializer\Serializer;

class UserManager
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(Serializer $serializer, ObjectManager $em)
    {
        $this->serializer = $serializer;
        $this->em = $em;
    }

    public function updateOrCreate(string $json): User
    {
        $user = $this->serializer->deserialize($json, User::class, 'json');

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
