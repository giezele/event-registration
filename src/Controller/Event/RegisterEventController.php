<?php

declare(strict_types=1);

namespace App\Controller\Event;

use App\Entity\Event;
use App\Entity\User;
use App\Form\EventRegistrationType;
use App\Service\EventRegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterEventController extends AbstractController
{
    public function __construct(private EventRegistrationService $eventRegistrationService)
    {
    }

    #[Route('/events/register/{id}', name: 'event_register', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, Event $event): Response
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
                $this->eventRegistrationService->registerUserForEvent($event, $user);
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
}
