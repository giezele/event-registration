<?php

declare(strict_types=1);

namespace App\Service\Event;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class EventRegistrationService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function registerUserForEvent(Event $event, User $user): void
    {
        if ($event->getAvailableSpots() <= 0) {
            throw new \Exception('No spots available for this event.');
        }

        $event->addUser($user);
        $event->setAvailableSpots($event->getAvailableSpots() - 1);

        $this->em->persist($user);
        $this->em->flush();
    }
}
