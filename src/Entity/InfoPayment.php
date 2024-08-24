<?php

namespace App\Entity;

use App\Repository\InfoPaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfoPaymentRepository::class)]
class InfoPayment extends BaseEntity
{
    #[ORM\Column(length: 255)]
    private ?string $customerId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $paymentMethod = null;

    #[ORM\OneToOne(mappedBy: 'infoPayment', cascade: ['persist', 'remove'])]
    private ?User $client = null;

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function setCustomerId(string $customerId): static
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        // unset the owning side of the relation if necessary
        if ($client === null && $this->client !== null) {
            $this->client->setInfoPayment(null);
        }

        // set the owning side of the relation if necessary
        if ($client !== null && $client->getInfoPayment() !== $this) {
            $client->setInfoPayment($this);
        }

        $this->client = $client;

        return $this;
    }
}
