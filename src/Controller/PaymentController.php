<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Manager\PaymentManager;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/payments")
 */
class PaymentController extends AbstractController
{
    /**
     * @var PaymentManager
     */
    private $manager;


    public function __construct(PaymentManager $manager)
    {
        $this->manager = $manager;
    }


    /**
     * @Route(name="payments_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $payments = $this->manager->index();

        return $this->json($payments);
    }

    /**
     * @Route(name="payments_create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $payment = Payment::create()
            ->setTitle($request->get('title'))
            ->setDescription($request->get('description'))
            ->setDeleted(false)
            ->setOperation($request->get('operation'))
            ->setValue($request->get('value'));

        try {
            $payment = $this->manager->create($payment);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json($payment, 201);
    }

    /**
     * @Route("/{paymentId}", name="payments_find", methods={"GET"})
     */
    public function find(Request $request, string $paymentId): JsonResponse
    {
        $payment = $this->manager->find($paymentId);

        if (!$payment) {
            return $this->responseErrorString("Pagamento não encontrado!");
        }

        return $this->json($payment);
    }

    /**
     * @Route("/{paymentId}", name="payments_remove", methods={"DELETE"})
     */
    public function remove(string $paymentId): JsonResponse
    {
        $payment = $this->manager->find($paymentId);

        if (!$payment) {
            return $this->responseErrorString("Pagamento não encontrado!");
        }

        try {
            $this->manager->remove($payment);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json(null);
    }
}
