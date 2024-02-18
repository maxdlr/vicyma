<?php

namespace App\Entity;

use App\Repository\LodgingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LodgingRepository::class)]
class Lodging
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\Column]
    private ?int $roomCount = null;

    #[ORM\Column]
    private ?float $surface = null;

    #[ORM\Column]
    private ?int $bathroomCount = null;

    #[ORM\Column]
    private ?int $toiletCount = null;

    #[ORM\Column]
    private ?bool $tvService = null;

    #[ORM\Column]
    private ?bool $washer = null;

    #[ORM\Column]
    private ?bool $waterHeater = null;

    #[ORM\Column]
    private ?bool $parking = null;

    #[ORM\Column]
    private ?bool $gate = null;

    #[ORM\Column]
    private ?bool $animalAllowed = null;

    #[ORM\Column]
    private ?bool $terrace = null;

    #[ORM\Column(nullable: true)]
    private ?float $terraceSurface = null;

    #[ORM\Column]
    private ?int $floor = null;

    #[ORM\Column(length: 1000)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $priceByNight = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getRoomCount(): ?int
    {
        return $this->roomCount;
    }

    public function setRoomCount(int $roomCount): static
    {
        $this->roomCount = $roomCount;

        return $this;
    }

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(float $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function getBathroomCount(): ?int
    {
        return $this->bathroomCount;
    }

    public function setBathroomCount(int $bathroomCount): static
    {
        $this->bathroomCount = $bathroomCount;

        return $this;
    }

    public function getToiletCount(): ?int
    {
        return $this->toiletCount;
    }

    public function setToiletCount(int $toiletCount): static
    {
        $this->toiletCount = $toiletCount;

        return $this;
    }

    public function hasTvService(): ?bool
    {
        return $this->tvService;
    }

    public function setTvService(bool $tvService): static
    {
        $this->tvService = $tvService;

        return $this;
    }

    public function hasWasher(): ?bool
    {
        return $this->washer;
    }

    public function setWasher(bool $washer): static
    {
        $this->washer = $washer;

        return $this;
    }

    public function hasWaterHeater(): ?bool
    {
        return $this->waterHeater;
    }

    public function setWaterHeater(bool $waterHeater): static
    {
        $this->waterHeater = $waterHeater;

        return $this;
    }

    public function hasParking(): ?bool
    {
        return $this->parking;
    }

    public function setParking(bool $parking): static
    {
        $this->parking = $parking;

        return $this;
    }

    public function hasGate(): ?bool
    {
        return $this->gate;
    }

    public function setGate(bool $gate): static
    {
        $this->gate = $gate;

        return $this;
    }

    public function isAllowAnimals(): ?bool
    {
        return $this->animalAllowed;
    }

    public function setAllowAnimals(bool $animalAllowed): static
    {
        $this->animalAllowed = $animalAllowed;

        return $this;
    }

    public function hasTerrace(): ?bool
    {
        return $this->terrace;
    }

    public function setTerrace(bool $terrace): static
    {
        $this->terrace = $terrace;

        return $this;
    }

    public function getTerraceSurface(): ?float
    {
        return $this->terraceSurface;
    }

    public function setTerraceSurface(?float $terraceSurface): static
    {
        $this->terraceSurface = $terraceSurface;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): static
    {
        $this->floor = $floor;

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

    public function getPriceByNight(): ?float
    {
        return $this->priceByNight;
    }

    public function setPriceByNight(float $priceByNight): static
    {
        $this->priceByNight = $priceByNight;

        return $this;
    }
}
