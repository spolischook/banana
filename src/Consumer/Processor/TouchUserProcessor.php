<?php

namespace App\Consumer\Processor;

use App\Consumer\Message;
use App\Consumer\Processor\Message\MessageInterface;
use App\Consumer\Processor\Message\TouchUserMessage;
use App\Entity\User;
use App\Ig\IgSingleton;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use InstagramAPI\Instagram;
use Psr\Log\LoggerInterface;

class TouchUserProcessor extends AbstractProcessor
{
    protected $logger;
    /** @var Instagram */
    protected $ig;
    /** @var EntityManager */
    protected $em;

    public function __construct(
        LoggerInterface $logger,
        IgSingleton $igSingleton,
        ObjectManager $em
    ) {
        $this->logger = $logger;
        $this->ig = $igSingleton->getIg();
        $this->em = $em;
    }

    /**
     * @param MessageInterface|TouchUserMessage $message
     */
    public function process(MessageInterface $message): void
    {
        $userResponse = $this->ig->people->getInfoById($message->getUserId());

        if ($userResponse->getUser()->isIsPrivate()) {
            $this->logger->alert('This is private account, skip it');
            return;
        }

        $countOfItemsToLike = rand(4, 7);
        $this->logger->warning(sprintf(
            'Touch "%s" user with %s rundom likes',
            $userResponse->getUser()->getUsername(),
            $countOfItemsToLike
        ));
        $maxId = null;

        do {
            $response = $this->ig->timeline->getUserFeed($userResponse->getUser()->getPk());
            foreach ($response->getItems() as $item) {
                if ($item->getHasLiked()) {
                    $this->logger->info(sprintf(
                        'Item was liked before, skipped "https://www.instagram.com/p/%s"',
                        $item->getCode()
                    ));
                    continue;
                }
                if (2 === rand(1, 3)) {
                    $this->ig->media->like($item->getId());
                    $this->logger->info(
                        sprintf(
                            'Like number %s "https://www.instagram.com/p/%s"',
                            $countOfItemsToLike,
                            $item->getCode()
                        )
                    );
                    if (--$countOfItemsToLike === 0) {
                        break 2;
                    }
                    $this->waitFor(5, 10, 'Wait before next like');
                }
            }

            $maxId = $response->getNextMaxId();
            $this->waitFor(20, 40, 'Wait before next page');
        } while ($maxId !== null && $countOfItemsToLike !== 0);

        $this->ig->people->follow($userResponse->getUser()->getPk());

        $this->em
            ->find(User::class, $userResponse->getUser()->getPk())
            ->setIFollow(true);
        $this->em->flush();

        $this->waitFor(30, 60, 'Wait before next processor');
    }

    protected function getSupportedMessages(): array
    {
        return [TouchUserMessage::class];
    }
}
