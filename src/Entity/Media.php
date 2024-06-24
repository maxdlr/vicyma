<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $mediaPath = null;

    #[ORM\Column]
    private ?float $mediaSize = null;

    #[ORM\ManyToMany(targetEntity: Lodging::class, inversedBy: 'medias')]
    private Collection $lodgings;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $createdOn;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updatedOn = null;

    public function __construct()
    {
        $this->lodgings = new ArrayCollection();
        $this->createdOn = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaPath(): ?string
    {
        return $this->mediaPath;
    }

    public function setMediaPath(string $mediaPath): static
    {
        $this->mediaPath = $mediaPath;

        return $this;
    }

    public function getMediaSize(): ?float
    {
        return $this->mediaSize;
    }

    public function setMediaSize(float $mediaSize): static
    {
        $this->mediaSize = $mediaSize;

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

    public function setUpdatedOn(?DateTimeInterface $updatedOn): static
    {
        $this->updatedOn = $updatedOn;
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
}
