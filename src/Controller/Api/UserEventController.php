<?php

namespace App\Controller\Api;

use App\Entity\UserEvent;
use App\Repository\EventRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;

class UserEventController
{
    /**
     * @Rest\View(serializerGroups={"Default"})
     * @Rest\Get("/user-events/count")
     * @QueryParam(name="from", nullable=true, requirements="\d{4}-\d{2}-\d{2}")
     * @QueryParam(name="to", nullable=true, requirements="\d{4}-\d{2}-\d{2}")
     * @QueryParam(map=true, name="discr", nullable=true, requirements="\S+")
     * @param EventRepository $repository
     * @param ParamFetcher $paramFetcher
     */
    public function getEventsAction(EventRepository $repository, ParamFetcher $paramFetcher)
    {
        $from = $paramFetcher->get('from') ?: null;
        $to = $paramFetcher->get('to') ?: null;

        if ($from) {
            $from = new \DateTime($from);
            $from->setTime(0, 0, 0);
        }

        if ($to) {
            $to = new \DateTime($to);
            $to->setTime(23, 59, 59);
        }

        $events = $repository->getEvents($from, $to, $paramFetcher->get('discr'));

        return count($events);
    }
}
