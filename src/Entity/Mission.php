<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $startTime = null;

    #[ORM\Column(length: 255)]
    private ?string $scheduleTime = null;

    #[ORM\Column(length: 255)]
    private ?string $hours = null;

    #[ORM\Column(length: 255)]
    private ?string $intervention = null;

    #[ORM\Column(length: 255)]
    private ?string $missionReference = null;

    #[ORM\Column(length: 255)]
    private ?string $remuneration = null;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    private ?Clients $client = null;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    private ?Courses $course = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $beginAt;

    #[ORM\Column(type: 'datetime', nullable:true)]
    private ?\DateTimeInterface $endAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function setStartTime(string $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getScheduleTime(): ?string
    {
        return $this->scheduleTime;
    }

    public function setScheduleTime(string $scheduleTime): static
    {
        $this->scheduleTime = $scheduleTime;

        return $this;
    }

    public function getHours(): ?string
    {
        return $this->hours;
    }

    public function setHours(string $hours): static
    {
        $this->hours = $hours;

        return $this;
    }

    public function getIntervention(): ?string
    {
        return $this->intervention;
    }

    public function setIntervention(string $intervention): static
    {
        $this->intervention = $intervention;

        return $this;
    }

    public function getMissionReference(): ?string
    {
        return $this->missionReference;
    }

    public function setMissionReference(string $missionReference): static
    {
        $this->missionReference = $missionReference;

        return $this;
    }

    public function getRemuneration(): ?string
    {
        return $this->remuneration;
    }

    public function setRemuneration(string $remuneration): static
    {
        $this->remuneration = $remuneration;

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

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getCourse(): ?Courses
    {
        return $this->course;
    }

    public function setCourse(?Courses $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->beginAt;
    }

    public function setBeginAt(\DateTimeInterface $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt = null): self
    {
        $this->endAt = $endAt;

        return $this;
    }

}