<?php

namespace App\Controller;

use App\DBAL\Types\PaymentOperationType;
use App\Entity\Payment;
use App\Manager\PaymentManager;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

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

        if (count($payments) <= 0) {
            return $this->responseErrorString('Pagamentos não encontrados!', 404);
        }

        return $this->json($payments);
    }

    /**
     * @Route(name="payments_create", methods={"POST"})
     */
    public function create(): JsonResponse
    {
        $payment = Payment::create()
            ->setTitle($this->getField('title'))
            ->setDescription($this->getField('description'))
            ->setOperation($this->getField('operation'))
            ->setValue($this->getField('value'));

        try {
            $payment = $this->manager->create($payment);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json($payment, 201);
    }

    /**
     * @Route(name="payments_update", methods={"PUT"})
     */
    public function update(): JsonResponse
    {
        $payment = $this->manager->find($this->getField('id'));

        if (!$payment) {
            return $this->responseErrorString('Pagamento não encontrado!', 404);
        }

        $payment->setTitle($this->getField('title'))
            ->setDescription($this->getField('description'))
            ->setOperation($this->getField('operation'))
            ->setValue($this->getField('value'));

        try {
            $payment = $this->manager->create($payment);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json($payment, 200);
    }

    /**
     * @Route("/expense", name="payments_find_expense", methods={"GET"})
     */
    public function findExpense(): JsonResponse
    {
        $payments = $this->manager->findPayments(PaymentOperationType::PAYMENT_EXPENSE);

        if (count($payments) <= 0) {
            return $this->responseErrorString('Pagamentos não encontrados!', 404);
        }

        return $this->json($payments);
    }

    /**
     * @Route("/revenue", name="payments_find_revenue", methods={"GET"})
     */
    public function findRevenue(): JsonResponse
    {
        $payments = $this->manager->findPayments(PaymentOperationType::PAYMENT_REVENUE);

        if (count($payments) <= 0) {
            return $this->responseErrorString('Pagamentos não encontrados!', 404);
        }

        return $this->json($payments);
    }

    /**
     * @Route("/{paymentId}", name="payments_find", methods={"GET"})
     */
    public function find(string $paymentId): JsonResponse
    {
        $payment = $this->manager->find($paymentId);

        if (!$payment) {
            return $this->responseErrorString('Pagamento não encontrado!', 404);
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
            return $this->responseErrorString('Pagamento não encontrado!', 404);
        }

        $payment->setDeleted(true);

        try {
            $this->manager->create($payment);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json(null);
    }
}
