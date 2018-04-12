<?php

namespace App\Repository;

use App\Entity\UserUnfollowEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserUnfollowEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserUnfollowEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserUnfollowEvent[]    findAll()
 * @method UserUnfollowEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserUnfollowEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserUnfollowEvent::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('u')
            ->where('u.something = :value')->setParameter('value', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
