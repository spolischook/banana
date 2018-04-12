<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\Serializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    /**
     * @Rest\View(serializerGroups={"Default"})
     * @Rest\Get("/users")
     * @QueryParam(name="limit", default=10, requirements="\d+")
     * @QueryParam(name="offset", nullable=true, requirements="\d+")
     * @param EntityManager $em
     */
    public function getUsersAction(ObjectManager $em, ParamFetcher $paramFetcher)
    {
        return $em->getRepository(User::class)->findBy(
            ['userType' => User::FOUND],
            $paramFetcher->get('offset'),
            $paramFetcher->get('limit')
        );
    }

    /**
     * @Rest\Get("/users/count")
     * @param EntityManager $em
     */
    public function getFoundUsersCountAction(ObjectManager $em)
    {
        return $em->getRepository(User::class)->getFoundUserCount();
    }

    /**
     * @Rest\View(serializerGroups={"details", "Default"})
     * @Rest\Get("/users/{username}")
     * @param EntityManager $em
     */
    public function getUserAction($username, ObjectManager $em)
    {
        return $em->getRepository(User::class)->findOneBy(['username' => $username]);
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/users")
     */
    public function patchUserAction(Request $request, Serializer $serializer, ObjectManager $em)
    {
        $serializer->deserialize($request->getContent(), User::class, 'json');
        $em->flush();
        return ['success' => true];
    }
}
