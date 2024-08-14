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
    public function __construct(ManagerRegistry $registry, private CityRepository $cityRepository)
    {
        parent::__construct($registry, Garage::class);
    }

    public function findBySearch(?string $text = null, ?string $cityId = null, ?int $minPrice = null, ?int $maxPrice = null): Query
    {
        $queryBuilder = $this->createQueryBuilder('g')
            ->select('g', 'i', 'c')
            ->leftJoin('g.images', 'i')
            ->leftJoin('g.city', 'c')
            ->where('g.deletedAt IS NULL');

        // Filtre par nom
        if ($text) {
            $queryBuilder->andWhere('g.name LIKE :text')
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


        return $queryBuilder->getQuery();
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
