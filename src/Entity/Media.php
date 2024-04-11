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
    private ?string $mediaName = null;

    #[ORM\Column()]
    private ?float $mediaSize = null;

    #[ORM\ManyToMany(targetEntity: Lodging::class, inversedBy: 'medias')]
    private Collection $lodgings;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $createdOn = null;

    public function __construct()
    {
        $this->lodgings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaName(): ?string
    {
        return $this->mediaName;
    }

    public function setMediaName(string $mediaName): static
    {
        $this->mediaName = $mediaName;

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

    public function getCreatedOn(): ?DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(DateTimeInterface $createdOn): static
    {
        $this->createdOn = $createdOn;

        return $this;
    }
}
