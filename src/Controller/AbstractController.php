<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AbstractController extends BaseController
{
    protected function responseErrorException(Exception $exception): JsonResponse
    {
        $message = [
            "errors" => [
                [
                    "message" => $exception->getMessage(),
                    "code" => $exception->getCode(),
                ]
            ],
        ];

        return $this->json($message, 500);
    }

    protected function responseErrorString(string $message): JsonResponse
    {
        $message = [
            "errors" => [
                [
                    "message" => $message,
                    "code" => null,
                ]
            ],
        ];

        return $this->json($message, 500);
    }
}
