<?php

namespace App\EventSubscriber;

use App\Exception\NoSpotsAvailableException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RouterInterface $router,
        private RequestStack $requestStack
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NoSpotsAvailableException) {
            $request = $this->requestStack->getCurrentRequest();

            if ($request !== null) {
                $session = $request->getSession();
                $session->getFlashBag()->add('error', $exception->getMessage());

                $response = new RedirectResponse($this->router->generate('event_index'));
                $event->setResponse($response);
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
