<?php

namespace App\Manager;

use App\Entity\User;
use Exception;

class UserManager extends AbstractManager {
    public function find(?string $email): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $email,
        ]);
    }

    public function create(User $user): User
    {
        $this->validateObject($user);

        $this->entityManager->beginTransaction();

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $user;
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }

    public function remove(User $user): void
    {
        $this->entityManager->beginTransaction();

        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }
}