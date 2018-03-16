<?php

namespace App\Controller\Web;

use App\Consumer\Message;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("")
     */
    public function homeAction(Producer $producer, SerializerInterface $serializer)
    {
        $message = new Message('update_my_feed');
        $message->data = ['maxId' => null];
        $producer->publish($serializer->serialize($message, 'json'));

        return new Response('Home action is work');
    }
}
