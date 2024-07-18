<?php

namespace App\Entity;

use App\Repository\GarageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Constraints;

#[ORM\Entity(repositoryClass: GarageRepository::class)]
#[ORM\Index( name: 'address_idx', columns: ['address'])]
#[ORM\Index( name: 'description_idx', columns: ['description'])]
#[ORM\Index( name: 'price_per_day_idx', columns: ['price_per_day'])]
#[ORM\Index( name: 'price_per_hour_idx', columns: ['price_per_hour'])]
class Garage extends BaseEntity
{
    #[ORM\Column(type: Types::TEXT)]
    #[Constraints\NotBlank]
    private ?string $address = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Constraints\NotBlank]
    #[Constraints\Length(min: 10)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Constraints\NotBlank]
    private ?string $pricePerHour = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Constraints\NotBlank]
    private ?string $pricePerDay = null;

    #[ORM\ManyToOne(inversedBy: 'garages')]
    private ?User $owner = null;

    /**
     * @var Collection<int, GarageAvailability>
     */
    #[ORM\OneToMany(targetEntity: GarageAvailability::class, mappedBy: 'garage', cascade: ['persist'])]
    private Collection $availability;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'garage')]
    private Collection $reservations;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'garage')]
    private Collection $reviews;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'garage')]
    private Collection $images;

    #[ORM\ManyToOne(inversedBy: 'garages')]
    private ?City $city = null;

    #[ORM\Column(length: 255)]
    #[Constraints\NotBlank]
    #[Constraints\Length(min: 3, max: 255)]
    private ?string $name = null;

    public function __construct()
    {
        parent::__construct();
        $this->availability = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPricePerHour(): ?string
    {
        return $this->pricePerHour;
    }

    public function setPricePerHour(string $pricePerHour): static
    {
        $this->pricePerHour = $pricePerHour;

        return $this;
    }

    public function getPricePerDay(): ?string
    {
        return $this->pricePerDay;
    }

    public function setPricePerDay(string $pricePerDay): static
    {
        $this->pricePerDay = $pricePerDay;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, GarageAvailability>
     */
    public function getAvailability(): Collection
    {
        return $this->availability;
    }

    public function addAvailability(GarageAvailability $availability): static
    {
        if (!$this->availability->contains($availability)) {
            $this->availability->add($availability);
            $availability->setGarage($this);
        }

        return $this;
    }

    public function removeAvailability(GarageAvailability $availability): static
    {
        if ($this->availability->removeElement($availability)) {
            // set the owning side to null (unless already changed)
            if ($availability->getGarage() === $this) {
                $availability->setGarage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setGarage($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getGarage() === $this) {
                $reservation->setGarage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setGarage($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getGarage() === $this) {
                $review->setGarage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setGarage($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getGarage() === $this) {
                $image->setGarage(null);
            }
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
