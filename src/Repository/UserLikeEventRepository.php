<?php

namespace App\Repository;

use App\Entity\UserLikeEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserLikeEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLikeEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLikeEvent[]    findAll()
 * @method UserLikeEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLikeEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserLikeEvent::class);
    }

//    /**
//     * @return UserLikeEvent[] Returns an array of UserLikeEvent objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserLikeEvent
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
