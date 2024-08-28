<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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
    public function findByTenant(User $user, ?int $status): Query
    {
        $query = $this->createQueryBuilder('r')
            ->select('r', 'g', 'p', 'renter', 'city', "image")
            ->leftJoin('r.garage', 'g')
            ->leftJoin('r.payment', 'p')
            ->leftJoin('r.renter', 'renter')
            ->leftJoin('g.city', 'city')
            ->leftJoin("g.images", "image")
            ->where('r.deletedAt IS NULL')
            ->andWhere('r.tenant = :user')
            ->setParameter('user', $user)
            ->orderBy('r.startAt', 'ASC')
        ;

        if($status) {
            $query->andWhere('r.status = :status')
                ->setParameter('status', $status);
        }

        return $query->getQuery();
    }
}
