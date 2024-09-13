<?php

namespace App\EventListener;

use App\Entity\Notification;
use App\Event\NotificationEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NotificationListener implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NotificationEvent::NAME => 'onNotificationCreated',
        ];
    }

    public function onNotificationCreated(NotificationEvent $event): void
    {
        $user = $event->user;
        $message = $event->message;
        $type = $event->type;
        $relatedEntityId = $event->relatedEntityId;

        $notification = new Notification();
        $notification->setRead(false);
        $notification->setUserId($user);
        $notification->setType($type);
        $notification->setMessage($message);
        $notification->setRelatedEntityId($relatedEntityId);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}