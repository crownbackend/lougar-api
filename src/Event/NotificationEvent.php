<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Uid\Uuid;


class NotificationEvent extends Event
{
    public const NAME = 'notification.created';

    public function __construct(public User $user, public string $message, public string $type, public Uuid $relatedEntityId)
    {
    }
}