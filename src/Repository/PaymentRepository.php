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
        $query = $this->createQueryBuilder('p')
            ->select('p', 'r')
            ->join('p.reservation', 'r');

        if(in_array('ROLE_TENANT', $user->getRoles())) {
            $query->where('r.tenant = :user');
        } else if(in_array('ROLE_OWNER', $user->getRoles())) {
            $query->where('r.renter = :user');
        }
        $query->setParameter('user', $user);

        return $query->getQuery()->getResult();
    }
}
