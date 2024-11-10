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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConversationManager
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private ConversationRepository $conversationRepository,
                                private MessageRepository $messageRepository, private ValidatorInterface $validator)
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

    public function createMessage(Conversation $conversation, User $user, array $data): Message|array
    {
        $constraints = new Assert\Collection([
            'content' => [
                new Assert\NotBlank(['message' => 'Le message ne peut pas être vide.']),
                new Assert\Length([
                    'max' => 1000,
                    'maxMessage' => 'Le message ne peut pas dépasser {{ limit }} caractères.'
                ]),
                new Assert\Type([
                    'type' => 'string',
                    'message' => 'Le message doit être une chaîne de caractères valide.'
                ]),
                new Assert\Regex([
                    'pattern' => '/^[\w\s,.!?éèêëàâäôöîïùûüç\'-]*$/iu', // Facultatif : seulement lettres, chiffres, espaces et certains signes de ponctuation
                    'message' => 'Le message contient des caractères non valides.',
                ]),
            ]
        ]);

        // Valider le contenu
        $violations = $this->validator->validate(['content' => $data['content']], $constraints);

        if (count($violations) > 0) {
            // Retourner les erreurs si validation échoue
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            return ['success' => false, 'errors' => $errors];
        }
        $message = new Message();
        $message->setConversation($conversation);
        $message->setSenderId($user->getId());
        $message->setContent($data['content']);
        $this->entityManager->persist($message);
        $this->entityManager->flush();
        return $message;
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