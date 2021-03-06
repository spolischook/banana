<?php

namespace App\EventListener;

use App\Consumer\Processor\Message\TouchUserMessage;
use App\Entity\UserTypeEvent;
use App\Entity\User;
use App\Entity\UserFollowEvent;
use App\Entity\UserUnfollowEvent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use App\Producer;

class UserChangeListener
{
    private $producer;

    public function __construct(Producer $producer)
    {
        $this->producer = $producer;
    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        if (!$entities = $uow->getScheduledEntityUpdates()) {
            return;
        }

        /** @var User|Object $entity */
        foreach ($entities as $entity) {
            if (User::class !== get_class($entity)) {
                continue;
            }

            $changeSet = $uow->getEntityChangeSet($entity);

            $this->touchUserTask($entity, $changeSet);
            $this->changeUserStatusEvent($entity, $em, $changeSet);
            $this->userFollowEvent($entity, $em, $changeSet);
        }

        $em->getUnitOfWork()->computeChangeSets();
    }

    protected function userFollowEvent(User $entity, EntityManager $em, array $changeSet)
    {
        if (!isset($changeSet['isFollower'])) {
            return;
        }

        list($oldValue, $newValue) = $changeSet['isFollower'];

        if (true === $newValue) {
            $event = new UserFollowEvent();
        } else {
            $event = new UserUnfollowEvent();
        }

        $event->setUser($entity);

        $em->persist($event);
    }

    protected function changeUserStatusEvent(User $entity, EntityManager $em, array $changeSet)
    {
        if (!isset($changeSet['userType'])) {
            return;
        }

        list($oldValue, $newValue) = $changeSet['userType'];

        $event = new UserTypeEvent();
        $event
            ->setNewType($newValue)
            ->setOldType($oldValue)
            ->setUser($entity);

        $em->persist($event);
        $em->getUnitOfWork()->computeChangeSets();
    }

    protected function touchUserTask(User $entity, array $changeSet)
    {
        if (!isset($changeSet['userType'])) {
            return;
        }

        list($oldValue, $newValue) = $changeSet['userType'];

        if (User::FOUND === $oldValue && User::INTERESTING_USER === $newValue) {
            $message = new TouchUserMessage();
            $message->setUserId($entity->getPk());

            $this->producer->publish($message);
        }
    }
}
