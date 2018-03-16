<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\Serializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    /**
     * @Rest\View(serializerGroups={"Default"})
     * @Rest\Get("/users")
     * @param EntityManager $em
     */
    public function getUsersAction(ObjectManager $em)
    {
            return $em->getRepository(User::class)->findBy(['userType' => null], null, 10);
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
    public function setUserTypeAction(Request $request, Serializer $serializer, ObjectManager $em)
    {
        $serializer->deserialize($request->getContent(), User::class, 'json');
        $em->flush();
        return ['success' => true];
    }
}
