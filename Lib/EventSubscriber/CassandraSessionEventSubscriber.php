<?php

namespace Alfatraining\CassandraSessionHandlerBundle\Lib\EventSubscriber;

use Alfatraining\CassandraSessionHandlerBundle\Lib\Session\Storage\Handler\CassandraSessionHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CassandraSessionEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var \SessionHandlerInterface
     */
    private $sessionHandler;

    public function __construct(\SessionHandlerInterface $sessionHandler)
    {
        $this->sessionHandler = $sessionHandler;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 256],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$this->sessionHandler instanceof CassandraSessionHandler) {
            return;
        }

        $this->sessionHandler->setUp();
    }
}
