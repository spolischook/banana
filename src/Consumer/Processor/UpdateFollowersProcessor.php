<?php

namespace App\Consumer\Processor;

use App\Consumer\Message;
use App\Entity\User;
use App\Ig\IgSingleton;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use InstagramAPI\Instagram;
use InstagramAPI\Request\Collection;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Psr\Log\LoggerInterface;

class UpdateFollowersProcessor extends AbstractProcessor
{
    protected $type = 'update_followers';
    protected $logger;
    /** @var Instagram */
    protected $ig;
    protected $producer;
    /** @var EntityManager */
    protected $em;
    protected $followers;

    public function __construct(
        LoggerInterface $logger,
        IgSingleton $igSingleton,
        Producer $producer,
        ObjectManager $em
    ) {
        $this->logger = $logger;
        $this->ig = $igSingleton->getIg();
        $this->producer = $producer;
        $this->em = $em;
    }

    public function process(Message $message): void
    {
        $this->logger->warning('Update the my followers');
        $me = $this->ig->account->getCurrentUser()->getUser();
        $maxId = $message->data['maxId'];

        if (!$maxId) {
            $this->dumpFollowers();
        }

        $followersResponse = $this->ig->people->getFollowers($me->getPk(), null, $maxId);

        foreach ($followersResponse->getUsers() as $user) {
        }
    }

    private function dumpFollowers()
    {
        $this->followers = $this->em->getRepository(User::class)->findBy(['isFollower' => true]);
    }

    private function followerFollow($pk)
    {
        $this->followers = array_filter($this->followers, function(User $user) use ($pk) {
            return $user->getPk() !== $pk;
        });
    }
}
