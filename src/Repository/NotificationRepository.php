<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

        /**
         * @return Notification[] Returns an array of Notification objects
         */
        public function findByUser(User $user): array
        {
            return $this->createQueryBuilder('n')
                ->where('n.userId = :user')
                ->andWhere('n.isRead = :isRead')
                ->setParameter('user', $user)
                ->setParameter('isRead', false)
                ->orderBy('n.createdAt', 'DESC')
                ->getQuery()
                ->getResult()
            ;
        }
}
