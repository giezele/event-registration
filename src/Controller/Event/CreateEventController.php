<?php

declare(strict_types=1);

namespace App\Controller\Event;

use App\Entity\Event;
use App\Form\EventType;
use App\Service\EventCreationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateEventController extends AbstractController
{
    public function __construct(private EventCreationService $eventCreationService)
    {
    }

    #[Route('/events/create', name: 'event_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->eventCreationService->createEvent($event);

            $this->addFlash('success', 'Event created successfully!');
            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
