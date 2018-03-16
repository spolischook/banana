<?php

namespace App\Consumer\Processor;

use App\Consumer\Message;
use App\Entity\Comment;
use App\Entity\Item;
use App\Entity\User;
use App\Ig\IgSingleton;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use InstagramAPI\Instagram;
use JMS\Serializer\Serializer;
use Psr\Log\LoggerInterface;

class UpdateMyItemProcessor extends AbstractProcessor
{
    protected $type = 'update_my_item';
    protected $logger;
    /** @var Instagram */
    protected $ig;
    protected $serializer;
    /** @var EntityManager */
    protected $em;

    public function __construct(
        LoggerInterface $logger,
        IgSingleton $igSingleton,
        Serializer $serializer,
        ObjectManager $em
    ) {
        $this->logger = $logger;
        $this->ig = $igSingleton->getIg();
        $this->serializer = $serializer;
        $this->em = $em;
    }

    public function process(Message $message): void
    {
        $this->logger->warning('Update my item');
        $itemId = $message->data['itemId'];
        $this->logger->info('Update info');
        $response = $this->ig->media->getInfo($itemId);
        $item = $response->getItems()[0];

        $this->logger->info('Update item https://www.instagram.com/p/'.$item->getCode());
        /** @var Item $item */
        $item = $this->serializer->deserialize($item, Item::class, 'json');
        $this->em->persist($item);
        $this->em->flush();

        $this->logger->info('Update likers of item');

        $response = $this->ig->media->getLikers($itemId);
        foreach ($response->getUsers() as $user) {
            $user = $this->serializer->deserialize($user, User::class, 'json');
            $this->em->persist($user);
            $item->addLiker($user);
        }

        $this->em->flush();

        $maxId = null;
        $this->logger->info('Update comments');

        do {
            $response = $this->ig->media->getComments($item->getId());

            foreach ($response->getComments() as $comment) {
                $comment = $this->serializer->deserialize($comment, Comment::class, 'json');
                $this->em->persist($comment);
                $this->em->flush();
            }
            
            $maxId = $response->getNextMaxId();
            $this->waitFor(5, 8, 'Wait for next page of comments');
        } while ($maxId !== null);

        $this->waitFor(10, 20, 'Wait after update item');
    }
}
