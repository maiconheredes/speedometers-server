<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $event->setResponse(new JsonResponse([
            "errors" => [
                [
                    "message" => $exception->getMessage(),
                    "code" => $exception->getCode(),
                ]
            ],
        ]));
    }
}
