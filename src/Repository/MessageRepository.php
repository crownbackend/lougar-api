<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findByCount(Conversation $conversation, Uuid $uuid): int
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->where('m.conversation = :conversation')
            ->andWhere('m.senderId != :senderId')
            ->andWhere('m.readAt IS NULL')
            ->setParameter('conversation', $conversation)
            ->setParameter('senderId', $uuid)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
