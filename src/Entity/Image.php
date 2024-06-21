<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image extends BaseEntity
{
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Garage $garage = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPrincipal = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getGarage(): ?Garage
    {
        return $this->garage;
    }

    public function setGarage(?Garage $garage): static
    {
        $this->garage = $garage;

        return $this;
    }

    public function isPrincipal(): ?bool
    {
        return $this->isPrincipal;
    }

    public function setPrincipal(?bool $isPrincipal): static
    {
        $this->isPrincipal = $isPrincipal;

        return $this;
    }
}
