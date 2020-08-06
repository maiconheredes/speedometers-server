<?php

namespace App\Manager;

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
}