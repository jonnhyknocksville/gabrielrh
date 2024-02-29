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

    #[ORM\Column(length: 255)]
    private ?string $timeTable = null;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    private ?Students $student = null;

    #[ORM\Column(length: 255)]
    private ?string $hourlyRate = null;

    #[ORM\Column]
    private ?int $nbrDays = null;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    private ?Tarification $tarification = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $orderNumber = null;

    #[ORM\Column(nullable: true)]
    private ?bool $teacherPaid = null;

    #[ORM\Column(nullable: true)]
    private ?bool $clientPaid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $background = null;

    #[ORM\Column]
    private ?bool $invoiceSent = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'invoice_client_missions')]
    private ?Clients $invoice_client = null;

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

    public function getTimeTable(): ?string
    {
        return $this->timeTable;
    }

    public function setTimeTable(string $timeTable): static
    {
        $this->timeTable = $timeTable;

        return $this;
    }

    public function getStudent(): ?Students
    {
        return $this->student;
    }

    public function setStudent(?Students $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getHourlyRate(): ?string
    {
        return $this->hourlyRate;
    }

    public function setHourlyRate(string $hourlyRate): static
    {
        $this->hourlyRate = $hourlyRate;

        return $this;
    }

    public function getNbrDays(): ?int
    {
        return $this->nbrDays;
    }

    public function setNbrDays(int $nbrDays): static
    {
        $this->nbrDays = $nbrDays;

        return $this;
    }

    public function getTarification(): ?Tarification
    {
        return $this->tarification;
    }

    public function setTarification(?Tarification $tarification): static
    {
        $this->tarification = $tarification;

        return $this;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?string $orderNumber): static
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function isTeacherPaid(): ?bool
    {
        return $this->teacherPaid;
    }

    public function setTeacherPaid(?bool $teacherPaid): static
    {
        $this->teacherPaid = $teacherPaid;

        return $this;
    }

    public function isClientPaid(): ?bool
    {
        return $this->clientPaid;
    }

    public function setClientPaid(?bool $clientPaid): static
    {
        $this->clientPaid = $clientPaid;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(?string $background): static
    {
        $this->background = $background;

        return $this;
    }

    public function isInvoiceSent(): ?bool
    {
        return $this->invoiceSent;
    }

    public function setInvoiceSent(bool $invoiceSent): static
    {
        $this->invoiceSent = $invoiceSent;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getInvoiceClient(): ?Clients
    {
        return $this->invoice_client;
    }

    public function setInvoiceClient(?Clients $invoice_client): static
    {
        $this->invoice_client = $invoice_client;

        return $this;
    }


}
