<?php

namespace App\Controller;

use App\Entity\PaymentHistory;
use App\Manager\CashierManager;
use App\Manager\PaymentHistoryManager;
use App\Manager\PaymentManager;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payment-histories")
 */
class PaymentHistoryController extends AbstractController
{
    /**
     * @var PaymentHistoryManager
     */
    private $manager;

    /**
     * @var PaymentManager
     */
    private $paymentManager;

    /**
     * @var CashierManager
     */
    private $cashierManager;


    public function __construct(
        PaymentHistoryManager $manager,
        PaymentManager $paymentManager,
        CashierManager $cashierManager
    ) {
        $this->manager = $manager;
        $this->paymentManager = $paymentManager;
        $this->cashierManager = $cashierManager;  
    }

    /**
     * @Route(name="paymentHistories_create", methods={"POST"});
     */
    public function create(): JsonResponse
    {
        $payment = $this->paymentManager->find($this->getField('payment.id'));
        $cashier = $this->cashierManager->find($this->getField('cashier.id'));

        if (!$payment) {
            return $this->responseErrorString('Pagamento não encontrado!', 404);
        }

        if (!$cashier) {
            return $this->responseErrorString('Caixa não encontrado!', 404);
        }

        $paymentHistory = PaymentHistory::create()
            ->setPayment($payment)
            ->setCashier($cashier)
            ->setValue($payment->getValue());

        try {
            $paymentHistory = $this->manager->create($paymentHistory);
        } catch (Exception $exception) {
            return $this->responseErrorException($exception);
        }

        return $this->json($paymentHistory, 201);
    }
}
