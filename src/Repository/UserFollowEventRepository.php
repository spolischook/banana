<?php

namespace App\Repository;

use App\Entity\UserFollowEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserFollowEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFollowEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFollowEvent[]    findAll()
 * @method UserFollowEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFollowEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserFollowEvent::class);
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
