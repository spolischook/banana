<?php

namespace App\Tests\EventListener;

use App\Entity\Item;
use App\Entity\User;
use App\Entity\UserLikeEvent;
use App\Tests\Ig\CleanDb;

class ItemChangeListenerTest extends CleanDb
{
    public function testOnFlush()
    {
        $this->loadFixtures(__DIR__.'/fixtures/users.yaml');
        $this->loadFixtures(__DIR__.'/fixtures/item.yaml');
        /** @var Item $user */
        $item = $this->getEm()->getRepository(Item::class)->findOneBy([]);
        /** @var User $user */
        $user = $this->getEm()->getRepository(User::class)->findOneBy([]);
        $item->addLiker($user);
        $this->getEm()->flush();

        $event = $this->getEm()->getRepository(UserLikeEvent::class)->findOneBy([]);

        self::assertNotNull($event);
        self::assertEquals($user->getPk(), $event->getUser()->getPk());
        self::assertEquals($item->getId(), $event->getItem()->getId());
    }

    protected function getClass()
    {
        return Item::class;
    }
}
