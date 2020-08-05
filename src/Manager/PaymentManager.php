<?php

namespace App\Manager;

use App\Entity\Payment;

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
}