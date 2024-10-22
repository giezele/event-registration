<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\EventType;
use App\Form\EventRegistrationType;
use App\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    public function __construct(private EventService $eventService)
    {
    }

    #[Route('/events/create', name: 'event_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->eventService->createEvent($event);

            $this->addFlash('success', 'Event created successfully!');
            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/events/register/{id}', name: 'event_register', methods: ['GET', 'POST'])]
    public function register(Request $request, Event $event): Response
    {
        // Prevent access to the registration form if no spots are available
        if ($event->getAvailableSpots() <= 0) {
            $this->addFlash('error', 'No spots available for this event.');
            return $this->redirectToRoute('event_index');
        }

        $user = new User();
        $form = $this->createForm(EventRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->eventService->registerUserForEvent($event, $user);
                $this->addFlash('success', 'You have successfully registered for the event!');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/register.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    #[Route('/events', name: 'event_index')]
    public function index(): Response
    {
        $events = $this->eventService->getAllEvents();

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }
}
