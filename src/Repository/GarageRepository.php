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

    public function findBySearch(?string $text = null, ?string $cityId = null, ?string $minPrice = null, ?string $maxPrice = null): Query
    {
        $queryBuilder = $this->createQueryBuilder('g')
            ->select('g', 'i', 'c')
            ->leftJoin('g.images', 'i')
            ->leftJoin('g.city', 'c');

        // Filtre par nom
        if ($text) {
            $queryBuilder->andWhere('LOWER(g.name) LIKE LOWER(:text)')
                ->orWhere('LOWER(g.description) LIKE LOWER(:text)')
                ->orWhere('LOWER(g.address) LIKE LOWER(:text)')
                ->setParameter('text', '%' . $text . '%');
        }

        // Filtre par ville
        if ($cityId) {
            $queryBuilder->andWhere('c.id = :city')
                ->setParameter('city', $cityId);
        }

        // Filtre par plage de prix (soit par heure, soit par jour)
        if ($minPrice !== null || $maxPrice !== null) {
            $queryBuilder->andWhere(
                '(g.pricePerHour BETWEEN :minPrice AND :maxPrice OR g.pricePerDay BETWEEN :minPrice AND :maxPrice)'
            )
                ->setParameter('minPrice', $minPrice)
                ->setParameter('maxPrice', $maxPrice);
        }


        return $queryBuilder->andWhere('g.deletedAt IS NULL')
            ->getQuery();
    }

    public function findMinMaxPrices(): array
    {
        $qb = $this->createQueryBuilder('g');

        $qb->select(
            'MIN(g.pricePerHour) as minPrice',
            'MAX(g.pricePerDay) as maxPrice'
        )->where('g.deletedAt IS NULL');

        return $qb->getQuery()->getSingleResult();
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
            ->orderBy('c.name', 'ASC')
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
