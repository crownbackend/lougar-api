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
    public function findByUser(User $user, ?int $status = null, string $typeUser): Query|array
    {
        $query = $this->createQueryBuilder('r')
            ->select('r', 'g', 'p', 'city', "image")
            ->leftJoin('r.garage', 'g')
            ->leftJoin('r.payment', 'p')
            ->leftJoin('g.city', 'city')
            ->leftJoin("g.images", "image")
            ->where('r.deletedAt IS NULL')
            ->orderBy('r.status', 'ASC')
        ;

        if($typeUser === "owner") {
            $query
                ->leftJoin("r.renter", "renter")
                ->andWhere('renter = :owner')
                ->setParameter('owner', $user);
        }

        if($typeUser === "tenant") {
            $query
                ->leftJoin("r.tenant", "tenant")
                ->andWhere('tenant = :tenant')
                ->setParameter('tenant', $user);
        }

        if($status) {
            $query->andWhere('r.status = :status')
                ->setParameter('status', $status);
        }

        if($typeUser === "owner") {
            return $query->getQuery()->getResult();
        } else {
            return $query->getQuery();
        }
    }
}
