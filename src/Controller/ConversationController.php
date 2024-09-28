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
    public function createMessage(Conversation $conversation, Request $request): RedirectResponse
    {
        $data = $request->request->all();
        $conversation = $this->manager->createMessage($conversation, $this->getUser(), $data);
        return $this->redirectToRoute('my_conversation_index', [
            'id' => $conversation?->getId(),
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

    public function publish(HubInterface $hub): Response
    {
        $update = new Update(
            'https://example.com/books/1',
            json_encode(['status' => 'OutOfStock'])
        );

        $hub->publish($update);

        return new Response('published!');
    }
}
