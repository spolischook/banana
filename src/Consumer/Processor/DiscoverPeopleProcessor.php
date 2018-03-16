<?php

namespace App\Consumer\Processor;

use App\Consumer\Message;
use App\Ig\IgSingleton;
use App\Ig\ItemManager;
use App\Ig\UserManager;
use InstagramAPI\Signatures;
use Psr\Log\LoggerInterface;

class DiscoverPeopleProcessor extends AbstractProcessor
{
    protected $type = 'discover_people';
    protected $logger;
    protected $uuid;
    protected $ig;
    protected $userManager;
    protected $itemManager;

    public function __construct(
        LoggerInterface $logger,
        IgSingleton $igSingleton,
        UserManager $userManager,
        ItemManager $itemManager
    ) {
        $this->logger = $logger;
        $this->ig = $igSingleton->getIg();
        $this->userManager = $userManager;
        $this->itemManager = $itemManager;
        $this->uuid = Signatures::generateUUID();
    }

    public function process(Message $message): void
    {
        $maxId = null;
        $tag = 'family';

        do {
            $response = $this->ig->hashtag->getFeed($tag, $this->uuid, $maxId);
            $response = $this->ig->location->findPlaces();
            foreach ($response->getItems() as $item) {
                $user = $item->getUser();
                $jsonUser = $this->ig->people->getInfoByName($user->getUsername())->asJson();
                $this->logger->info(sprintf('Save "%s" user', $user->getFullName()));
                $this->userManager->updateOrCreate($jsonUser);
                $this->logger->info('Save users timeline feed');
                $feed = $this->ig->timeline->getUserFeed($user->getPk());

                foreach ($feed->getItems() as $userItem) {
                    $this->itemManager->updateOrCreate($userItem);
                    $this->logger->info('Saved user item');
                }
            }
            $maxId = $response->getNextMaxId();
            $waitTime = rand(5, 10);
            $this->logger->info(sprintf('Waiting %s seconds', $waitTime));
            sleep($waitTime);
        } while ($maxId !== null);
    }
}
