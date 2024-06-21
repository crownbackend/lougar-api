<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<City>
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function findByName(string $query): array
    {
        $normalized = str_replace(' ', '-', strtolower($query));
        $qb = $this->createQueryBuilder('c');

        $qb->addSelect(
            '(CASE '.
            'WHEN c.slug LIKE :exact THEN 3 '.
            'WHEN c.slug LIKE :prefix THEN 2 '.
            'ELSE 1 '.
            'END) as HIDDEN relevance'
        )
            ->where($qb->expr()->like('c.slug', ':query'))
            ->setParameter('exact', $normalized)
            ->setParameter('prefix', $normalized . '%')
            ->setParameter('query', '%' . $normalized . '%')
            ->orderBy('relevance', 'DESC')
            ->addOrderBy('c.name', 'ASC')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

    public function findByPostalCode(string $query): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.postalCode LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }
}
