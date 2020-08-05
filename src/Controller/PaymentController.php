<?php

namespace App\Controller;

use App\Manager\PaymentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payments")
 */
class PaymentController extends AbstractController
{
    /**
     * @var PaymentManager
     */
    private $manager;


    public function __construct(PaymentManager $manager)
    {
        $this->manager = $manager;
    }


    /**
     * @Route("/", name="payments_index")
     */
    public function index(Request $request): Response
    {
        $payments = $this->manager->index();

        return $this->json($payments);
    }
}
