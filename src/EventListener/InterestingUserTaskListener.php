<?php

namespace App\EventListener;

use App\Consumer\Message;
use App\Consumer\Processor\Message\TouchUserMessage;
use App\Entity\User;
use Doctrine\ORM\Event\OnFlushEventArgs;
use App\Producer;

class InterestingUserTaskListener
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

            if (!isset($changeSet['userType'])) {
                continue;
            }

            list($oldValue, $newValue) = $changeSet['userType'];

            if (User::FOUND === $oldValue && User::INTERESTING_USER === $newValue) {
                $message = new TouchUserMessage();
                $message->setUserId($entity->getPk());

                $this->producer->publish($message);
            }
        }
    }
}
