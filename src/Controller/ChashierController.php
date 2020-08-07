<?php

namespace App\Controller;

use App\Entity\Cashier;
use App\Manager\CashierManager;
use App\Manager\PaymentHistoryManager;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cashiers")
 */
class ChashierController extends AbstractController
{
    /**
     * @var CashierManager
     */
    private $manager;

    /**
     * @var PaymentHistoryManager
     */
    private $paymentHistoryManager;


    public function __construct(
        CashierManager $manager, 
        PaymentHistoryManager $paymentHistoryManager
    ) {
        $this->manager = $manager;
        $this->paymentHistoryManager = $paymentHistoryManager;
    }


    /**
     * @Route(name="cashiers_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $cashiers = $this->manager->index();

        if (count($cashiers) <= 0) {
            return $this->responseErrorString('Caixas não encontrados!', 404);
        }

        return $this->json($cashiers);
    }

    /**
     * @Route(name="cashiers_create", methods={"POST"})
     */
    public function create(): JsonResponse
    {
        $cashier = Cashier::create()
            ->setTitle($this->getField('title'))
            ->setDescription($this->getField('description'));

        try {
            $cashier = $this->manager->create($cashier);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json($cashier, 201);
    }

    /**
     * @Route(name="cashiers_update", methods={"PUT"})
     */
    public function update(): JsonResponse
    {
        $cashier = $this->manager->find($this->getField('id'));

        if (!$cashier) {
            return $this->responseErrorString('Caixa não encontrado!', 404);
        }

        $cashier->setTitle($this->getField('title'))
            ->setDescription($this->getField('description'));

        try {
            $payment = $this->manager->create($cashier);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json($cashier, 200);
    }

    /**
     * @Route("/{cashierId}", name="cashiers_find", methods={"GET"})
     */
    public function find(string $cashierId): JsonResponse
    {
        $cashier = $this->manager->find($cashierId);

        if (!$cashier) {
            return $this->responseErrorString('Caixa não encontrado!', 404);
        }

        return $this->json($cashier);
    }

    /**
     * @Route("/{cashierId}", name="cashiers_remove", methods={"DELETE"})
     */
    public function remove(string $cashierId): JsonResponse
    {
        $cashier = $this->manager->find($cashierId);

        if (!$cashier) {
            return $this->responseErrorString('Caixa não encontrado!', 404);
        }

        try {
            $this->manager->remove($cashier);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json(null);
    }
}
