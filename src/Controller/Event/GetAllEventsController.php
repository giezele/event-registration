<?php

declare(strict_types=1);

namespace App\Controller\Event;

use App\Service\EventListingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetAllEventsController extends AbstractController
{
    public function __construct(private EventListingService $eventListingService)
    {
    }

    #[Route('/events', name: 'event_index', methods: ['GET'])]
    public function __invoke(): Response
    {
        $events = $this->eventListingService->getAllEvents();

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }
}
