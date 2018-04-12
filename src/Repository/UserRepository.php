<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getFoundUserCount()
    {
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('count(u.pk)')
            ->where('u.userType=:type')
            ->setParameter('type', User::FOUND)
            ->from(User::class, 'u')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Find users that was not active
     * @param int $activityDays
     * @return array|User[]
     */
    public function findUngratefulUsers(int $activityDays = 30)
    {
        $eventCountDql = $this->getEntityManager()->createQueryBuilder()
            ->select('count(ue.id)')
            ->from(UserEvent::class, 'ue')
            ->where('ue.user=u.pk')
            ->andWhere('ue.date > :date')
            ->getDQL();
        $qb = $this->createQueryBuilder('u');
        $query = $qb
            ->select('u')
            ->addSelect(sprintf('(%s) as eventCount', $eventCountDql))
            ->where('u.isFollower = :isFollower')
            // Maybe it worth to apply to all users?
            // Remove this condition along with setParameter
            ->andWhere('u.userType = :userType')
            ->andWhere('u.iFollow = :iFollow')
            ->having('eventCount=0')
            ->setParameter('isFollower', false)
            ->setParameter('userType', User::INTERESTING_USER)
            ->setParameter('iFollow', true)
            ->setParameter('date', new \DateTime(sprintf('- %d days', $activityDays)))
            ->getQuery()
        ;

        // Because virtual field "eventCount" appears in results
        // Try to delete "eventCount" from results and remove this array_map
        return array_map(function ($item) {
            return $item[0];
        }, $query->getResult());
    }
}
