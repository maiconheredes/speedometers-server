<?php

namespace App\Manager;

use App\DBAL\Types\PaymentOperationType;
use App\Entity\Cashier;
use App\Entity\PaymentHistory;
use Exception;

class PaymentHistoryManager extends AbstractManager {
    public function create(PaymentHistory $paymentHistory): PaymentHistory
    {
        $this->validateObject($paymentHistory);

        $this->entityManager->beginTransaction();

        try {
            $this->entityManager->persist($paymentHistory);
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $paymentHistory;
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }

    /**
     * @return PaymentHistory[]
     */
    public function index()
    {
        return $this->getRepository(PaymentHistory::class)->findAll();
    }

    public function find(string $paymentHistoryId): ?PaymentHistory
    {
        return $this->getRepository(PaymentHistory::class)->findOneBy([
            'id' => $paymentHistoryId,
        ]);
    }

    public function totalBaseByCashier(Cashier $cashier, string $operation): array
    {
        return $this->getRepository(PaymentHistory::class)
            ->createQueryBuilder('ph')
            ->select('SUM(ph.value) as totalValue')
            ->andWhere('ph.cashier = :cashier')
            ->andWhere('ph.operation = :operation')
            ->setParameter('operation', $operation)
            ->setParameter('cashier', $cashier)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function totalRevenueByCashier(Cashier $cashier): float
    {
        $result = $this->totalBaseByCashier($cashier, PaymentOperationType::PAYMENT_REVENUE);

        return $result['totalValue'] ?: 0;
    }

    public function totalExpenseByCashier(Cashier $cashier): float
    {
        $result = $this->totalBaseByCashier($cashier, PaymentOperationType::PAYMENT_EXPENSE);

        return $result['totalValue'] ?: 0;
    }
}