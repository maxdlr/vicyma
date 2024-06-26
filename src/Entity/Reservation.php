<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use App\ValueObject\ReservationNumber;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?string $reservationNumber = null;

    #[ORM\ManyToMany(targetEntity: Lodging::class, inversedBy: 'reservations')]
    private Collection $lodgings;

    #[ORM\Column]
    private ?int $adultCount = null;

    #[ORM\Column(nullable: true)]
    private ?int $childCount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $arrivalDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $departureDate = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?ReservationStatus $reservationStatus = null;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'reservation')]
    private Collection $messages;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $createdOn;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updatedOn = null;

    public function __construct()
    {
        $this->lodgings = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->createdOn = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservationNumber(): ?string
    {
        return $this->reservationNumber;
    }

    public function setReservationNumber(User $user, Reservation $reservation): static
    {

        $this->reservationNumber = ReservationNumber::new($user, $reservation);
        return $this;
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

    public function getArrivalDate(): ?DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(DateTimeInterface $arrivalDate): static
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function getDepartureDate(): ?DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(DateTimeInterface $departureDate): static
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

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setReservation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getReservation() === $this) {
                $message->setReservation(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedOn(): ?DateTimeInterface
    {
        return $this->createdOn;
    }

    public function getUpdatedOn(): ?DateTimeInterface
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn(?DateTimeInterface $updatedOn): Reservation
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }


}
