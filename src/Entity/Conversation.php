<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use App\ValueObject\ConversationId;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdOn = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedOn = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'conversation', cascade: ['remove', 'persist'])]
    private Collection $messages;

    #[ORM\Column(length: 100)]
    private ?string $conversationId = null;

    #[ORM\ManyToOne(inversedBy: 'conversations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $isArchivedByUser;

    #[ORM\Column]
    private ?bool $isArchivedByAdmin;

    public function __construct()
    {
        $this->createdOn = new DateTime();
        $this->messages = new ArrayCollection();
        $this->isArchivedByAdmin = false;
        $this->isArchivedByUser = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function getUpdatedOn(): ?\DateTimeInterface
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn(?\DateTimeInterface $updatedOn): static
    {
        $this->updatedOn = $updatedOn;

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
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }

    public function getConversationId(): ?string
    {
        return $this->conversationId;
    }

    public function setConversationId(string $conversationId): static
    {
        $this->conversationId = $conversationId;

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

    public function getIsArchivedByUser(): ?bool
    {
        return $this->isArchivedByUser;
    }

    public function setIsArchivedByUser(bool $isArchivedByUser): static
    {
        $this->isArchivedByUser = $isArchivedByUser;

        return $this;
    }

    public function getIsArchivedByAdmin(): ?bool
    {
        return $this->isArchivedByAdmin;
    }

    public function setIsArchivedByAdmin(bool $isArchivedByAdmin): static
    {
        $this->isArchivedByAdmin = $isArchivedByAdmin;

        return $this;
    }
}
