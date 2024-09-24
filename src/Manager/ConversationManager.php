<?php

namespace App\Manager;

use App\Entity\Conversation;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ConversationManager
{
    public function __construct(private EntityManagerInterface $entityManager, private ConversationRepository $conversationRepository)
    {
    }

    public function index(User $user): array
    {
        return $this->conversationRepository->findByUser($user);
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
}