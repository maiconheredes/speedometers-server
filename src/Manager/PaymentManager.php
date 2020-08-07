<?php

namespace App\Manager;

use App\Entity\Payment;
use App\Entity\PaymentHistory;
use Exception;

class PaymentManager extends AbstractManager {
    /**
     * @return Payment[]
     */
    public function index()
    {
        return $this->entityManager->getRepository(Payment::class)->findBy([
            'deleted' => false,
        ]);
    }

    public function find(?string $paymentId): ?Payment
    {
        return $this->entityManager->getRepository(Payment::class)->findOneBy([
            'deleted' => false,
            'id' => $paymentId,
        ]);
    }

    public function create(Payment $payment): Payment
    {
        $this->validateObject($payment);

        $this->entityManager->beginTransaction();

        try {
            $this->entityManager->persist($payment);
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $payment;
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }
}