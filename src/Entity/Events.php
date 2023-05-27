<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventsRepository::class)]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $dateString = null;

    #[ORM\Column(options: ['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?Users $users = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?Doctors $doctors = null;

    public function __construct(){
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDateString(): ?string
    {
        return $this->dateString;
    }

    public function setDateString(?string $dateString): self
    {
        $this->dateString = $dateString;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getDoctors(): ?Doctors
    {
        return $this->doctors;
    }

    public function setDoctors(?Doctors $doctors): self
    {
        $this->doctors = $doctors;

        return $this;
    }
}
