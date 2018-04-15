<?php

namespace App\Repository;

use App\Entity\UserEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserEvent[]    findAll()
 * @method UserEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserEvent::class);
    }

    public function getEvents(
        \DateTime $from = null,
        \DateTime $to = null,
        array $discr = null
    ) {
        $qb = $this->createQueryBuilder('ue');

        if ($discr) {
            $discrMap = $this->getEntityManager()->getClassMetadata(UserEvent::class)->discriminatorMap;

            foreach ($discr as $item) {
                if (!isset($discrMap[$item])) {
                    throw new \InvalidArgumentException(sprintf(
                        'Discriminator field "%s" is not registered',
                        $item
                    ));
                }

                $qb->orWhere($qb->expr()->isInstanceOf('ue', $discrMap[$item]));
            }
        }

        if ($from) {
            $qb
                ->andWhere('ue.date > :dateFrom')
                ->setParameter('dateFrom', $from);
        }

        if ($to) {
            $qb
                ->andWhere('ue.date < :dateTo')
                ->setParameter('dateTo', $to);
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
