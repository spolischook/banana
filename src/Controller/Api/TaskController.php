<?php

namespace App\Controller\Api;

use App\Consumer\TaskConsumer;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use App\Producer;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskController extends FOSRestController
{
    protected $producer;

    public function __construct(Producer $producer)
    {
        $this->producer = $producer;
    }

    /**
     * @Rest\Get("/tasks/discover-people")
     */
    public function discoverPeopleAction(TaskConsumer $taskConsumer)
    {
        $this->producer
            ->setContentType('application/json')
            ->publish(json_encode(['task' => 'discoverPeople']));

        return new JsonResponse(['success' => true]);
    }
}
