<?php

namespace App\Manager;

use App\Entity\Service;
use Exception;

class ServiceManager extends AbstractManager {
    /**
     * @return Service[]
     */
    public function index()
    {
        return $this->entityManager->getRepository(Service::class)->findBy([
            'deleted' => false,
        ]);
    }

    public function find(?string $serviceId): ?Service
    {
        return $this->entityManager->getRepository(Service::class)->findOneBy([
            'deleted' => false,
            'id' => $serviceId,
        ]);
    }

    public function create(Service $service): Service
    {
        $this->validateObject($service);

        $this->entityManager->beginTransaction();

        try {
            $this->entityManager->persist($service);
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $service;
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }
}