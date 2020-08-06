<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AbstractController extends BaseController
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    

    protected function responseErrorException(Exception $exception, int $httpCode = 500): JsonResponse
    {
        $message = [
            "errors" => [
                [
                    "message" => $exception->getMessage(),
                    "code" => $exception->getCode(),
                ]
            ],
        ];

        return $this->json($message, $httpCode);
    }

    protected function responseErrorString(string $message, int $httpCode = 500): JsonResponse
    {
        $message = [
            "errors" => [
                [
                    "message" => $message,
                    "code" => null,
                ]
            ],
        ];

        return $this->json($message, $httpCode);
    }

    protected function convertJsonToArray(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }

    /**
     * @return mixed
     */
    protected function getField(string $field, array $data = null)
    {
        if (is_null($data)) {
            $data = $this->convertJsonToArray(
                $this->requestStack->getCurrentRequest()
            );
        }

        $fields = explode('.', $field, 2);

        if (array_key_exists($fields[0], $data)) {
            if (is_array($data[$fields[0]]) && count($data[$fields[0]]) > 0) {
                if (isset($fields[1])) {
                    return $this->getField($fields[1], $data[$fields[0]]);
                } else {
                    return $data[$fields[0]];
                }
            } else return $data[$fields[0]] ?: null;
        }

        return null;
    }

    /**
     * @required
     */
    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }
}
