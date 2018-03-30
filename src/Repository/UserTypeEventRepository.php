<?php

namespace App\Repository;

use App\Entity\UserTypeEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserTypeEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTypeEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTypeEvent[]    findAll()
 * @method UserTypeEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTypeEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserTypeEvent::class);
    }

//    /**
//     * @return DiscoverUserEvent[] Returns an array of DiscoverUserEvent objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DiscoverUserEvent
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
