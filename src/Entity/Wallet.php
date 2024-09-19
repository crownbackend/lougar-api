<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet extends BaseEntity
{
    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $balance = null;

    #[ORM\OneToOne(mappedBy: 'wallet', cascade: ['persist', 'remove'])]
    private ?User $users = null;

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        // unset the owning side of the relation if necessary
        if ($users === null && $this->users !== null) {
            $this->users->setWallet(null);
        }

        // set the owning side of the relation if necessary
        if ($users !== null && $users->getWallet() !== $this) {
            $users->setWallet($this);
        }

        $this->users = $users;

        return $this;
    }
}
