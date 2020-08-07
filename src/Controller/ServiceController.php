<?php

namespace App\Controller;

use App\DBAL\Types\PaymentOperationType;
use App\Entity\Payment;
use App\Entity\Service;
use App\Manager\ServiceManager;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/services")
 */
class ServiceController extends AbstractController
{
    /**
     * @var ServiceManager
     */
    private $manager;


    public function __construct(ServiceManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route(name="services_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $services = $this->manager->index();

        if (count($services) <= 0) {
            return $this->responseErrorString('Serviços não encontrados!', 404);
        }

        return $this->json($services);
    }

    /**
     * @Route(name="services_create", methods={"POST"})
     */
    public function create(): JsonResponse
    {
        $name = $this->getField('name');

        $payment = Payment::create()
            ->setTitle("Serviço para $name")
            ->setDescription($this->getField('description'))
            ->setOperation(PaymentOperationType::PAYMENT_REVENUE)
            ->setValue($this->getField('payment.value'));
            
        $service = Service::create()
            ->setName($name)
            ->setCpf($this->getField('cpf'))
            ->setAddress($this->getField('address'))
            ->setDescription($this->getField('description'))
            ->setPayment($payment);

        try {
            $service = $this->manager->create($service);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json($service, 201);
    }

    /**
     * @Route(name="services_update", methods={"PUT"})
     */
    public function update(): JsonResponse
    {
        $service = $this->manager->find($this->getField('id'));

        if (!$service) {
            return $this->responseErrorString('Serviço não encontrado!', 404);
        }

        $name = $this->getField('name');
            
        $service->setName($name)
            ->setCpf($this->getField('cpf'))
            ->setAddress($this->getField('address'))
            ->setDescription($this->getField('description'))
            ->getPayment()
            ->setTitle("Serviço para $name")
            ->setDescription($this->getField('payment.description'))
            ->setValue($this->getField('payment.value'));

        try {
            $service = $this->manager->create($service);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json($service, 200);
    }

    /**
     * @Route("/{serviceId}", name="services_find", methods={"GET"})
     */
    public function find(string $serviceId): JsonResponse
    {
        $service = $this->manager->find($serviceId);

        if (!$service) {
            return $this->responseErrorString('Serviço não encontrado!', 404);
        }

        return $this->json($service);
    }

    /**
     * @Route("/{serviceId}", name="services_remove", methods={"DELETE"})
     */
    public function remove(string $serviceId): JsonResponse
    {
        $service = $this->manager->find($serviceId);

        if (!$service) {
            return $this->responseErrorString('Serviço não encontrado!', 404);
        }

        $service
            ->setDeleted(true)
            ->getPayment()->setDeleted(true);

        try {
            $this->manager->create($service);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json(null);
    }
}
