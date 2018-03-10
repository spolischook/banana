<?php

namespace App\Ig;

use App\Entity\Item;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use InstagramAPI\Instagram;
use JMS\Serializer\Serializer;

class ItemManager
{
    /**
     * @var Serializer
     */
    private $serializer;
    private $ig;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(Serializer $serializer, Instagram $ig, ObjectManager $em)
    {
        $this->serializer = $serializer;
        $this->ig = $ig;
        $this->em = $em;
    }

    public function updateOrCreate(string $json)
    {
        $id = json_decode($json, true)['id'];
        $item = $this->em->getRepository(Item::class)->find($id) ?: new Item();

        $item = $this->serializer->deserialize($json, Item::class, 'json');


        var_dump($item);
    }
}