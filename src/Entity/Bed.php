<?php

namespace App\Entity;

use App\Repository\BedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BedRepository::class)]
class Bed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $height = null;

    #[ORM\Column]
    private ?int $width = null;

    #[ORM\Column]
    private ?bool $isExtra = null;

    #[ORM\ManyToMany(targetEntity: Lodging::class, mappedBy: 'beds')]
    private Collection $lodgings;

    public function __construct()
    {
        $this->lodgings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function isIsExtra(): ?bool
    {
        return $this->isExtra;
    }

    public function setIsExtra(bool $isExtra): static
    {
        $this->isExtra = $isExtra;

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
            $lodging->addBed($this);
        }

        return $this;
    }

    public function removeLodging(Lodging $lodging): static
    {
        if ($this->lodgings->removeElement($lodging)) {
            $lodging->removeBed($this);
        }

        return $this;
    }
}
