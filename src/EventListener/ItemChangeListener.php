<?php

namespace App\EventListener;

use App\Entity\Item;
use App\Entity\User;
use App\Entity\UserLikeEvent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;

class ItemChangeListener
{
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        if (!$entities = $uow->getScheduledEntityUpdates()) {
            return;
        }

        /** @var Item|object $entity */
        foreach ($entities as $entity) {
            if (Item::class !== get_class($entity)) {
                continue;
            }

            $this->addUserLikeEvent($entity, $em);
        }

        $em->getUnitOfWork()->computeChangeSets();
    }

    public function addUserLikeEvent(Item $entity, EntityManager $em)
    {
        /** @var User $user */
        foreach ($entity->getLikers()->getDeleteDiff() as $user) {
            $event = new UserLikeEvent();
            $event
                ->setItem($entity)
                ->setLikeDirection(UserLikeEvent::TYPE_DISLIKE)
                ->setUser($user);
            $em->persist($event);
        }

        /** @var User $user */
        foreach ($entity->getLikers()->getInsertDiff() as $user) {
            $event = new UserLikeEvent();
            $event
                ->setItem($entity)
                ->setLikeDirection(UserLikeEvent::TYPE_LIKE)
                ->setUser($user);
            $em->persist($event);
        }
    }
}
