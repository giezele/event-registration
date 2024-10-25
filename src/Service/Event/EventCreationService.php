<?php

declare(strict_types=1);

namespace App\Service\Event;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;

class EventCreationService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function createEvent(Event $event): void
    {
        $this->em->persist($event);
        $this->em->flush();
    }
}
