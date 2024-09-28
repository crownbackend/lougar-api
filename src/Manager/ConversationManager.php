<?php

namespace App\Manager;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class ConversationManager
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private ConversationRepository $conversationRepository,
                                private MessageRepository $messageRepository)
    {
    }

    public function index(User $user): array
    {
        return $this->conversationRepository->findByUser($user);
    }

    public function messagesCount(Conversation $conversation, Uuid $uuid): int
    {
        return $this->messageRepository->findByCount($conversation, $uuid);
    }

    public function createMessage(Conversation $conversation, User $user, array $data): Conversation
    {
        // TODO check validator message
        $message = new Message();
        $message->setConversation($conversation);
        $message->setSenderId($user->getId());
        $message->setContent($data['content']);
        $this->entityManager->persist($message);
        $this->entityManager->flush();
        return $conversation;
    }

    public function create(Reservation $reservation, User $user): Conversation|null
    {
        if(!$reservation->getConversation()) {
            $tenant = $reservation->getTenant();
            $renter = $reservation->getRenter();
            if($user === $tenant || $renter === $user) {
                $conversation = new Conversation();
                $conversation->setReservation($reservation);
                $conversation->addUser($tenant);
                $conversation->addUser($renter);
                $this->entityManager->persist($conversation);
                $this->entityManager->flush();
                return $conversation;
            }
        } else if($reservation->getConversation()) {
            return $reservation->getConversation();
        }
        return null;
    }

    public function readMessages(array $data): void
    {
        $messages = $this->messageRepository->findBy(["id" => $data]);
        foreach ($messages as $message) {
            $message->setReadAt(new \DateTimeImmutable());
            $message->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($message);
        }
        $this->entityManager->flush();
    }
}