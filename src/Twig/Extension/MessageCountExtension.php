<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Entity\Conversation;
use App\Entity\User;
use App\Manager\ConversationManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MessageCountExtension extends AbstractExtension
{
    public function __construct(private ConversationManager $conversationManager, private TokenStorageInterface $tokenStorage)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('messagesCount', [$this, 'countMessages']),
        ];
    }

    public function countMessages(Conversation $conversation): ?int
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if (!$user instanceof User) {
            return null;
        }
        $id = $user->getId();
        if($this->conversationManager->messagesCount($conversation, $id) !== 0){
            return $this->conversationManager->messagesCount($conversation, $id);
        }
        return null;
    }
}
