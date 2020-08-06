<?php

namespace App\Manager;

use App\Entity\Payment;
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

    public function find(string $paymentId): ?Payment
    {
        return $this->entityManager->getRepository(Payment::class)->findOneBy([
            'deleted' => false,
            'id' => $paymentId,
        ]);
    }

    public function create(Payment $payment): Payment
    {
        $this->entityManager->beginTransaction();

        $this->validateObject($payment);

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

    public function remove(Payment $payment): void
    {
        $this->entityManager->beginTransaction();

        try {
            $this->entityManager->remove($payment);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }
}