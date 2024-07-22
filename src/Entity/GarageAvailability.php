<?php

namespace App\Entity;

use App\Repository\GarageAvailabilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GarageAvailabilityRepository::class)]
#[ORM\Index( name: 'start_at_idx', columns: ['start_at'])]
#[ORM\Index( name: 'end_at_idx', columns: ['end_at'])]
#[ORM\Index( name: 'day_of_week_idx', columns: ['day_of_week'])]
class GarageAvailability extends BaseEntity
{
    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\ManyToOne(inversedBy: 'availability')]
    private ?Garage $garage = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $dayOfWeek = null;

    /**
     * @var Collection<int, AvailabilityTime>
     */
    #[ORM\OneToMany(targetEntity: AvailabilityTime::class, mappedBy: 'garageAvailability', orphanRemoval: true)]
    private Collection $availabilityTimes;

    public function __construct()
    {
        parent::__construct();
        $this->availabilityTimes = new ArrayCollection();
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

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

    public function getDayOfWeek(): ?string
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(?string $dayOfWeek): static
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    /**
     * @return Collection<int, AvailabilityTime>
     */
    public function getAvailabilityTimes(): Collection
    {
        return $this->availabilityTimes;
    }

    public function addAvailabilityTime(AvailabilityTime $availabilityTime): static
    {
        if (!$this->availabilityTimes->contains($availabilityTime)) {
            $this->availabilityTimes->add($availabilityTime);
            $availabilityTime->setGarageAvailability($this);
        }

        return $this;
    }

    public function removeAvailabilityTime(AvailabilityTime $availabilityTime): static
    {
        if ($this->availabilityTimes->removeElement($availabilityTime)) {
            // set the owning side to null (unless already changed)
            if ($availabilityTime->getGarageAvailability() === $this) {
                $availabilityTime->setGarageAvailability(null);
            }
        }

        return $this;
    }
}
