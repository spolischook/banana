<?php

namespace App\EventListener;

use App\Consumer\Message;
use App\Entity\User;
use Doctrine\ORM\Event\OnFlushEventArgs;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

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

            if (null === $oldValue && User::INTERESTING_USER === $newValue) {
                $message = new Message('touch_user');
                $message->data = ['userId' => $entity->getPk()];

                $this->producer->publish(json_encode($message));
            }
        }
    }
}
