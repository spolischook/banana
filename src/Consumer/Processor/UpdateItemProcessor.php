<?php

namespace App\Consumer\Processor;

use App\Consumer\Message;
use App\Consumer\Processor\Message\MessageInterface;
use App\Consumer\Processor\Message\UpdateItemMessage;
use App\Entity\Comment;
use App\Entity\Item;
use App\Entity\User;
use App\Ig\CommentManager;
use App\Ig\IgSingleton;
use App\Ig\ItemManager;
use App\Ig\UserManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use InstagramAPI\Exception\EmptyResponseException;
use InstagramAPI\Instagram;
use JMS\Serializer\Serializer;
use Psr\Log\LoggerInterface;

class UpdateItemProcessor extends AbstractProcessor
{
    protected $logger;
    /** @var Instagram */
    protected $ig;
    protected $itemManager;
    protected $userManager;
    protected $commentManager;

    public function __construct(
        LoggerInterface $logger,
        IgSingleton $igSingleton,
        ItemManager $itemManager,
        UserManager $userManager,
        CommentManager $commentManager
    ) {
        $this->logger = $logger;
        $this->ig = $igSingleton->getIg();
        $this->itemManager = $itemManager;
        $this->userManager = $userManager;
        $this->commentManager = $commentManager;
    }

    /**
     * @param MessageInterface|UpdateItemMessage $message
     */
    public function process(MessageInterface $message): void
    {
        $this->logger->warning('Update item');
        $itemId = $message->getItemId();

        $this->logger->info('Update info');
        $response = $this->ig->media->getInfo($itemId);
        $item = $response->getItems()[0];

        $this->logger->info('Update item https://www.instagram.com/p/'.$item->getCode());
        /** @var Item $item */
        $item = $this->itemManager->updateOrCreate(json_encode($item));

        $this->logger->info('Update likers of item');

        $response = $this->ig->media->getLikers($itemId);
        foreach ($response->getUsers() as $user) {
            $user = $this->userManager->updateOrCreate($user->asJson());
            $item->addLiker($user);
        }

        $this->userManager->flush();

        $maxId = null;
        $this->logger->info('Update comments');

        do {
            try {
                $response = $this->ig->media->getComments($item->getId(), ['max_id' => $maxId]);
            } catch (EmptyResponseException $e) {
                $this->logger->error(sprintf(
                    'Can\'t get comments for "https://www.instagram.com/p/%s"',
                    $item->getCode()
                ));
                break;
            }

            foreach ($response->getComments() as $comment) {
                $this->commentManager->updateOrCreate($comment->asJson());
            }
            
            $maxId = $response->getNextMaxId();
            $this->waitFor(5, 8, 'Wait for next page of comments');
        } while ($maxId !== null);

        $this->waitFor(10, 20, 'Wait after update item');
    }

    protected function getSupportedMessages(): array
    {
        return [UpdateItemMessage::class];
    }
}
