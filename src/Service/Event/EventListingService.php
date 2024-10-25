<?php

declare(strict_types=1);

namespace App\Service\Event;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;

class EventListingService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getAllEvents(): array
    {
        return $this->em->getRepository(Event::class)->findAll();
    }
}
