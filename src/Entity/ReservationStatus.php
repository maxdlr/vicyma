<?php

namespace App\Entity;

use App\Repository\ReservationStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationStatusRepository::class)]
class ReservationStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Lodging::class, mappedBy: 'reservationStatus')]
    private Collection $lodgings;

    public function __construct()
    {
        $this->lodgings = new ArrayCollection();
    }

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
            $lodging->setReservationStatus($this);
        }

        return $this;
    }

    public function removeLodging(Lodging $lodging): static
    {
        if ($this->lodgings->removeElement($lodging)) {
            // set the owning side to null (unless already changed)
            if ($lodging->getReservationStatus() === $this) {
                $lodging->setReservationStatus(null);
            }
        }

        return $this;
    }
}
