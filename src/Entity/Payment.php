<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ORM\Index( name: 'amount_idx', columns: ['amount'])]
#[ORM\Index( name: 'commission_idx', columns: ['commission'])]
#[ORM\Index( name: 'status_idx', columns: ['status'])]
class Payment extends BaseEntity
{
    const float COMMISSION = 0.15;
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $commission = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $paymentAt = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\OneToOne(inversedBy: 'payment', cascade: ['persist', 'remove'])]
    private ?Reservation $reservation = null;

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCommission(): ?string
    {
        return $this->commission;
    }

    public function setCommission(string $commission): static
    {
        $this->commission = $commission;

        return $this;
    }

    public function getPaymentAt(): ?\DateTimeImmutable
    {
        return $this->paymentAt;
    }

    public function setPaymentAt(\DateTimeImmutable $paymentAt): static
    {
        $this->paymentAt = $paymentAt;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }
}
