<?php

namespace App\Manager;

use App\Entity\Cashier;
use Exception;

class CashierManager extends AbstractManager {
    /**
     * @var PaymentHistoryManager
     */
    protected $paymentHistoryManager;


    /**
     * @return Cashier[]
     */
    public function index()
    {
        $cashiers = $this->entityManager->getRepository(Cashier::class)->findAll();
        
        foreach ($cashiers as $cashier) {
            $totalValue = $this->paymentHistoryManager->totalRevenueByCashier($cashier);
            $cashier->setTotalValue(round($totalValue, 2));
        }

        return $cashiers;
    }

    public function find(?string $cashierId): ?Cashier
    {
        $cashier = $this->entityManager->getRepository(Cashier::class)->findOneBy([
            'id' => $cashierId,
        ]);

        $totalValue = $this->paymentHistoryManager->totalRevenueByCashier($cashier);

        $cashier->setTotalValue(round($totalValue, 2));

        return $cashier;
    }

    public function create(Cashier $cashier): Cashier
    {
        $this->validateObject($cashier);

        $this->entityManager->beginTransaction();

        try {
            $this->entityManager->persist($cashier);
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $cashier;
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }

    public function remove(Cashier $cashier): void
    {
        $this->entityManager->beginTransaction();

        try {
            $this->entityManager->remove($cashier);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }

    /**
     * @required
     */
    public function setPaymentHistoryManager(PaymentHistoryManager $paymentHistoryManager)
    {
        $this->paymentHistoryManager = $paymentHistoryManager;
    }
}