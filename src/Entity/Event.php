<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "The event name should not be blank.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "The event name cannot be longer than {{ limit }} characters."
    )]
    private string $name;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "The event date should not be blank.")]
    #[Assert\GreaterThan('today', message: "The event date must be in the future.")]
    private \DateTime $date;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "The event location should not be blank.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "The event location cannot be longer than {{ limit }} characters."
    )]
    private string $location;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: "The available spots should not be blank.")]
    #[Assert\Range(
        notInRangeMessage: "There must be at least {{ min }} available spot.",
        min: 1
    )]
    private int $availableSpots;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getAvailableSpots(): ?int
    {
        return $this->availableSpots;
    }

    public function setAvailableSpots(?int $availableSpots): static
    {
        $this->availableSpots = $availableSpots;

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
