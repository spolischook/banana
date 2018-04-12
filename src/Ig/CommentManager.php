<?php

namespace App\Ig;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use InstagramAPI\Instagram;
use JMS\Serializer\Serializer;

class CommentManager
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

    public function updateOrCreate(string $json): Comment
    {
        $comment = $this->serializer->deserialize($json, Comment::class, 'json');

        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }
}
