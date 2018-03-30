<?php

namespace App\Controller\Web;

use App\Consumer\Processor\Message\DiscoverPeopleByPlaceMessage;
use App\Consumer\Processor\Message\DiscoverPeopleByTagMessage;
use App\Consumer\Processor\Message\LikeMyFeedMessage;
use App\Consumer\Processor\Message\UngratefulUsersMessage;
use App\Consumer\Processor\Message\UpdateFollowersMessage;
use App\Consumer\Processor\Message\UpdateFollowListUsersMessage;
use App\Consumer\Processor\Message\UpdateMyFeedMessage;
use App\Entity\User;
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
    public function homeAction(Producer $producer, IgSingleton $igSingleton, ObjectManager $em)
    {
        $munichLocationId = '213359469';

//        $message = new DiscoverPeopleByTagMessage();
//        $message
//            ->setTag('munich')
//            ->setPageNumber(50);

//        $message = new DiscoverPeopleByPlaceMessage();
//        $message
//            ->setLocationId($munichLocationId)
//            ->setPageNumber(50);

//        $message = new UpdateMyFeedMessage();
//        $message->setPageNumber(20);

//        $message = new LikeMyFeedMessage();
//        $message->setPageNumber(10);

//        $message = new UpdateFollowersMessage();

//        $message = new UpdateFollowListUsersMessage();

        $message = new UngratefulUsersMessage();


        $producer->publish($message);

        return new Response('Home action is work'.PHP_EOL);
    }
}
