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
//            ->setPageNumber(10);
//
//        $message = new DiscoverPeopleByPlaceMessage();
//        $message
//            ->setLocationId($munichLocationId)
//            ->setPageNumber(10);

//        $message = new UpdateMyFeedMessage();
//        $message->setPageNumber(1);

//        $message = new LikeMyFeedMessage();
//        $message->setPageNumber(20);

//        $message = new UpdateFollowersMessage();

//        $message = new UpdateFollowListUsersMessage();
//
//        $message = new UngratefulUsersMessage();
//        $message->setUnActiveDays(5);


        $producer->publish($message);

        return new Response(sprintf('Message "%s" was added to queue'.PHP_EOL, get_class($message)));
    }
}
