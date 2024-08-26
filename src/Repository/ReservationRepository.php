<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @return Reservation[] Returns an array of Reservation objects
     */
    public function findByTenant(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'g', 'p', 'renter', 'city')
            ->leftJoin('r.garage', 'g')
            ->leftJoin('r.payment', 'p')
            ->leftJoin('r.renter', 'renter')
            ->leftJoin('g.city', 'city')
            ->where('r.deletedAt IS NULL')
            ->andWhere('r.tenant = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
