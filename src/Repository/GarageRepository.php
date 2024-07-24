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
            ->select('g', 'i', 'c', 'a')
            ->leftJoin('g.images', 'i')
            ->leftJoin('g.city', 'c')
            ->leftJoin('g.availability', 'a')
            ->where('g.owner = :owner')
            ->andWhere('g.deletedAt IS NULL')
            ->setParameter('owner', $owner)
            ->orderBy('g.createdAt', 'DESC')
            ->getQuery();
    }

    public function findById(string $id): Garage
    {
        return $this->createQueryBuilder('g')
            ->select('g', 'i', 'c', 'a', 'am')
            ->leftJoin('g.images', 'i')
            ->leftJoin('g.city', 'c')
            ->leftJoin('g.availability', 'a')
            ->leftJoin('a.availabilityTimes', 'am')
            ->where('g.id = :id')
            ->andWhere('g.deletedAt IS NULL')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
