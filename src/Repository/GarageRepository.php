<?php

namespace App\Repository;

use App\Entity\Garage;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Garage>
 */
class GarageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Garage::class);
    }

    public function findByOwner(User $owner): Query
    {
        return $this->createQueryBuilder('g')
            ->select('g', 'i', 'c')
            ->leftJoin('g.images', 'i')
            ->leftJoin('g.city', 'c')
            ->where('g.owner = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('g.createdAt', 'DESC')
            ->getQuery();
    }
}
