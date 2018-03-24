<?php

namespace App\Controller\Web;

use App\Consumer\Processor\Message\DiscoverPeopleByTagMessage;
use App\Consumer\Processor\Message\LikeMyFeedMessage;
use App\Consumer\Processor\Message\UpdateFollowersMessage;
use App\Consumer\Processor\Message\UpdateMyFeedMessage;
use App\Entity\User;
use App\EntityEvents\EventRepository;
use App\EntityEvents\UserDiscoverEvent;
use App\Ig\IgSingleton;
use App\Producer;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    /**
     * @Route("")
     */
    public function homeAction(Producer $producer, IgSingleton $igSingleton, ObjectManager $em, EventRepository $eventRepository)
    {
        $user = $em->getRepository(User::class)->findOneBy([]);
        $event = new UserDiscoverEvent($user);
        $eventRepository->insertEvent($event);
        echo 'done!';
        exit;










//        $message = new DiscoverPeopleByTagMessage();
//        $message
//            ->setTag('munich')
//            ->setPageNumber(10);

//        $message = new UpdateMyFeedMessage();
//        $message->setPageNumber(10);

//        $message = new LikeMyFeedMessage();
//        $message->setPageNumber(20);

        $message = new UpdateFollowersMessage();

        $producer->publish($message);

        return new Response('Home action is work');
    }
}
