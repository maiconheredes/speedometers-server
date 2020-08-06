<?php

namespace App\Manager;

use App\Entity\Cashier;
use Exception;

class CashierManager extends AbstractManager {
    /**
     * @return Cashier[]
     */
    public function index()
    {
        return $this->entityManager->getRepository(Cashier::class)->findAll();
    }

    public function find(?string $cashierId): ?Cashier
    {
        return $this->entityManager->getRepository(Cashier::class)->findOneBy([
            'id' => $cashierId,
        ]);
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
}