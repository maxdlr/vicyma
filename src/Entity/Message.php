<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 300)]
    private ?string $subject = null;

    #[ORM\Column(length: 2000)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Lodging $lodging = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Reservation $reservation = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn]
    private ?User $admin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $createdOn = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updatedOn = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'messages')]
    private ?Conversation $conversation = null;

    #[ORM\Column]
    private ?bool $isReadByAdmin = null;

    #[ORM\Column]
    private ?bool $isReadByUser = null;

    public function __construct()
    {
        $this->createdOn = new \DateTime();
        $this->isReadByAdmin = false;
        $this->isReadByUser = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getLodging(): ?Lodging
    {
        return $this->lodging;
    }

    public function setLodging(?Lodging $lodging): static
    {
        $this->lodging = $lodging;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

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

    public function getAdmin(): ?User
    {
        return $this->admin;
    }

    public function setAdmin(?User $admin): static
    {
        $this->admin = $admin;

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

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getIsReadByAdmin(): ?bool
    {
        return $this->isReadByAdmin;
    }

    public function setIsReadByAdmin(bool $isReadByAdmin): static
    {
        $this->isReadByAdmin = $isReadByAdmin;

        return $this;
    }

    public function getIsReadByUser(): ?bool
    {
        return $this->isReadByUser;
    }

    public function setIsReadByUser(bool $isReadByUser): static
    {
        $this->isReadByUser = $isReadByUser;

        return $this;
    }
}
