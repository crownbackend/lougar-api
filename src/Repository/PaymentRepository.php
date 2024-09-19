<?php

namespace App\Repository;

use App\Entity\Payment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payment>
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'r')
            ->join('p.reservation', 'r')
            ->where('r.renter = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
