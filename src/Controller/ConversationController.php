<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Reservation;
use App\Manager\ConversationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/mes-conversation', name: 'my_conversation_')]
class ConversationController extends AbstractController
{
    public function __construct(private ConversationManager $manager)
    {
    }

    #[Route('/{id}', name: 'index', defaults: ["id" => null], methods: ["GET", "POST"])]
    public function index(?Conversation $conversation = null): Response
    {
        $conversations = $this->manager->index($this->getUser());
        return $this->render('conversation/index.html.twig', [
            'conversations' => $conversations,
            'conversation' => $conversation,
        ]);
    }
    #[Route('/create/{id}/conversation', name: 'create_conversation')]
    public function createConversation(Reservation $reservation): RedirectResponse
    {
        $conversation = $this->manager->create($reservation, $this->getUser());
        return $this->redirectToRoute('my_conversation_index', [
            'id' => $conversation?->getId(),
        ]);
    }

    #[Route('/create/{id}/message', name: 'create_message')]
    public function createMessage(Conversation $conversation, Request $request, HubInterface $hub): JsonResponse
    {
        $data = $request->request->all();
        if (!$this->isCsrfTokenValid('send-message', $data['token'])) {
            throw new NotFoundHttpException('Error csrf token');
        }

        $message = $this->manager->createMessage($conversation, $this->getUser(), $data);
        // Publier un message via Mercure
        $update = new Update(
            'https://lougar.fr/chat/' . $conversation->getId(),
            json_encode([
                'message' => $message->getContent(),
                'senderId' => $this->getUser()->getId(),
                'createdAt' => $message->getCreatedAt()->format('Y-m-d H:i'),
                'readAt' => $message->getReadAt()?->format('Y-m-d H:i'),
            ])
        );
        $hub->publish($update);
        // Renvoie une réponse JSON
        return $this->json([
            'success' => true,
            'message' => [
                'content' => $message->getContent(),
                'createdAt' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
                'senderId' => $this->getUser()->getId()
            ]
        ]);
    }
    #[Route('/messages/read', name: 'read_messages', methods: ['POST'])]
    public function readMessages(Request $request): JsonResponse
    {
        if ($request->getContent()) {
            $data = json_decode($request->getContent(), true);
        } else {
            $data = $request->request->all();
        }
        $this->manager->readMessages($data);
        return $this->json('success', 201);
    }
}
