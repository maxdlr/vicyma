<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\Unique;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Unique]
    private ?string $reservationNumber = null;

    #[ORM\ManyToMany(targetEntity: Lodging::class, inversedBy: 'reservations')]
    private Collection $lodgings;

    #[ORM\Column]
    private ?int $adultCount = null;

    #[ORM\Column(nullable: true)]
    private ?int $childCount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $arrivalDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $departureDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?ReservationStatus $reservationStatus = null;

    public function __construct()
    {
        $this->lodgings = new ArrayCollection();
        $this->reservationNumber = Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservationNumber(): ?string
    {
        return $this->reservationNumber;
    }

    /**
     * @return Collection<int, Lodging>
     */
    public function getLodgings(): Collection
    {
        return $this->lodgings;
    }

    public function addLodging(Lodging $lodging): static
    {
        if (!$this->lodgings->contains($lodging)) {
            $this->lodgings->add($lodging);
        }

        return $this;
    }

    public function removeLodging(Lodging $lodging): static
    {
        $this->lodgings->removeElement($lodging);

        return $this;
    }

    public function getAdultCount(): ?int
    {
        return $this->adultCount;
    }

    public function setAdultCount(int $adultCount): static
    {
        $this->adultCount = $adultCount;

        return $this;
    }

    public function getChildCount(): ?int
    {
        return $this->childCount;
    }

    public function setChildCount(?int $childCount): static
    {
        $this->childCount = $childCount;

        return $this;
    }

    public function getArrivalDate(): ?\DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(\DateTimeInterface $arrivalDate): static
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function getDepartureDate(): ?\DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(\DateTimeInterface $departureDate): static
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getReservationStatus(): ?ReservationStatus
    {
        return $this->reservationStatus;
    }

    public function setReservationStatus(?ReservationStatus $reservationStatus): static
    {
        $this->reservationStatus = $reservationStatus;

        return $this;
    }
}
