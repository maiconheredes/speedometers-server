<?php

namespace App\Entity;

use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PaymentRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 * @ORM\Table(name="payments")
 */
class Payment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="O titulo não pode ser nulo.")
     * @Assert\NotBlank(message="O titulo não pode ser vazio.")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", options={"default": 0})
     * @Assert\NotNull(message="O valor não pode ser nulo.")
     * @Assert\NotBlank(message="O valor não pode ser vazio.")
     */
    private $value;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     * @Assert\NotNull(message="A parcela não pode ser nulo.")
     * @Assert\NotBlank(message="A parcela não pode ser vazio.")
     */
    private $installment;

    /**
     * @ORM\Column(type="integer", options={"default": 1})
     * @Assert\NotNull(message="As parcelas não pode ser nulo.")
     * @Assert\NotBlank(message="As parcelas não pode ser vazio.")
     */
    private $installments;

    /**
     * @ORM\Column(type="PaymentOperationType", length=255)
     * @Assert\NotNull(message="O tipo da operação não pode ser nulo.")
     * @Assert\NotBlank(message="O tipo da operação não pode ser vazio.")
     * @DoctrineAssert\Enum(entity="App\DBAL\Types\PaymentOperationType")
     */
    private $operation;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $deleted;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     */
    private $lastPayment;


    public function __construct()
    {
        $this->deleted = false;

        $this->value = 0;
        $this->installment = 0;
        $this->installments = 1;
    }

    public static function create(): self
    {
        return new self();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title = null): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(string $value = null): self
    {
        $this->value = $value;

        return $this;
    }

    public function getInstallment(): ?float
    {
        return $this->installment;
    }

    public function setInstallment(string $installment = null): self
    {
        $this->installment = $installment;

        return $this;
    }

    public function getInstallments(): ?float
    {
        return $this->installments;
    }

    public function setInstallments(string $installments = null): self
    {
        $this->installments = $installments;

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

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted = null): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getLastPayment(): ?\DateTimeInterface
    {
        return $this->lastPayment;
    }

    public function setLastPayment(?\DateTimeInterface $lastPayment): self
    {
        $this->lastPayment = $lastPayment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): self
    {
        $this->createdAt =  new DateTime();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new DateTime();

        return $this;
    }
}
