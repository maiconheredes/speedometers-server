<?php

namespace App\Entity;

use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PaymentHistoryRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=PaymentHistoryRepository::class)
 * @ORM\Table(name="payment_histories")
 */
class PaymentHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", options={"default": 0})
     * @Assert\NotNull(message="O valor não pode ser nulo.")
     * @Assert\NotBlank(message="O valor não pode ser vazio.")
     */
    private $value;

    /**
     * @ORM\Column(type="PaymentOperationType", length=255)
     * @Assert\NotNull(message="O tipo da operação não pode ser nulo.")
     * @Assert\NotBlank(message="O tipo da operação não pode ser vazio.")
     * @DoctrineAssert\Enum(entity="App\DBAL\Types\PaymentOperationType")
     */
    private $operation;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $paymentAt;

    /**
     * @Assert\NotNull(message="O pagamento não pode ser nulo.")
     * @Assert\NotBlank(message="O pagamento não pode ser vazio.")
     * @ORM\ManyToOne(targetEntity=Payment::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $payment;

    /**
     * @Assert\NotNull(message="A caixa não pode ser nulo.")
     * @Assert\NotBlank(message="A caixa não pode ser vazio.")
     * @ORM\ManyToOne(targetEntity=Cashier::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $cashier;


    public function __construct()
    {
        $this->value = 0;
    }

    public static function create(): self
    {
        return new self();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value = null): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setPaymentAt(): self
    {
        $this->paymentAt = new DateTime();

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getCashier(): ?Cashier
    {
        return $this->cashier;
    }

    public function setCashier(?Cashier $cashier): self
    {
        $this->cashier = $cashier;

        return $this;
    }

    public function getOperation(): ?string
    {
        return $this->operation;
    }

    public function setOperation(string $operation = null): self
    {
        $this->operation = $operation;

        return $this;
    }
}
