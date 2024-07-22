<?php

namespace App\Entity;

use App\Repository\AvailabilityTimeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvailabilityTimeRepository::class)]
#[ORM\Index( name: 'start_time_idx', columns: ['start_time'])]
#[ORM\Index( name: 'end_time_idx', columns: ['end_time'])]
class AvailabilityTime extends BaseEntity
{
    #[ORM\Column]
    private ?\DateTimeImmutable $startTime = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endTime = null;

    #[ORM\ManyToOne(inversedBy: 'availabilityTimes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GarageAvailability $garageAvailability = null;

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeImmutable $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeImmutable $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getGarageAvailability(): ?GarageAvailability
    {
        return $this->garageAvailability;
    }

    public function setGarageAvailability(?GarageAvailability $garageAvailability): static
    {
        $this->garageAvailability = $garageAvailability;

        return $this;
    }
}
