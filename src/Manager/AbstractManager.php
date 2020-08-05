<?php

namespace App\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractManager {
    /**
     * @var EntityManager
     */
    protected $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
