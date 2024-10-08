<?php

namespace App\Entity;

use App\Repository\JobsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobsRepository::class)]
class Jobs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $salary = null;

    #[ORM\Column(length: 255)]
    private ?string $contract = null;

    #[ORM\Column]
    private array $description = [];

    #[ORM\Column(length: 255)]
    private ?string $titleDescription = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $schedule = null;

    #[ORM\Column]
    private ?bool $available = null;

    #[ORM\Column(length: 255)]
    private ?string $missionDescription = null;

    #[ORM\Column]
    private array $mainMissions = [];

    #[ORM\Column(length: 255)]
    private ?string $profileDescription = null;

    #[ORM\Column]
    private array $profileRequirements = [];

    #[ORM\Column]
    private array $informations = [];

    #[ORM\ManyToMany(targetEntity: Advantages::class, inversedBy: 'jobs')]
    private Collection $advantages;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    private ?Courses $course = null;

    #[ORM\OneToMany(mappedBy: 'job', targetEntity: StaffApplication::class)]
    private Collection $staffApplications;

    #[ORM\OneToMany(mappedBy: 'job', targetEntity: JobApplication::class)]
    private Collection $jobApplications;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    private ?Themes $theme = null;

    public function __construct()
    {
        $this->advantages = new ArrayCollection();
        $this->staffApplications = new ArrayCollection();
        $this->jobApplications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(string $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getContract(): ?string
    {
        return $this->contract;
    }

    public function setContract(string $contract): static
    {
        $this->contract = $contract;

        return $this;
    }

    public function getDescription(): array
    {
        return $this->description;
    }

    public function setDescription(array $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTitleDescription(): ?string
    {
        return $this->titleDescription;
    }

    public function setTitleDescription(string $titleDescription): static
    {
        $this->titleDescription = $titleDescription;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    public function setSchedule(string $schedule): static
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function getMissionDescription(): ?string
    {
        return $this->missionDescription;
    }

    public function setMissionDescription(string $missionDescription): static
    {
        $this->missionDescription = $missionDescription;

        return $this;
    }

    public function getMainMissions(): array
    {
        return $this->mainMissions;
    }

    public function setMainMissions(array $mainMissions): static
    {
        $this->mainMissions = $mainMissions;

        return $this;
    }

    public function getProfileDescription(): ?string
    {
        return $this->profileDescription;
    }

    public function setProfileDescription(string $profileDescription): static
    {
        $this->profileDescription = $profileDescription;

        return $this;
    }

    public function getProfileRequirements(): array
    {
        return $this->profileRequirements;
    }

    public function setProfileRequirements(array $profileRequirements): static
    {
        $this->profileRequirements = $profileRequirements;

        return $this;
    }

    public function getInformations(): array
    {
        return $this->informations;
    }

    public function setInformations(array $informations): static
    {
        $this->informations = $informations;

        return $this;
    }

    /**
     * @return Collection<int, Advantages>
     */
    public function getAdvantages(): Collection
    {
        return $this->advantages;
    }

    public function addAdvantage(Advantages $advantage): static
    {
        if (!$this->advantages->contains($advantage)) {
            $this->advantages->add($advantage);
        }

        return $this;
    }

    public function removeAdvantage(Advantages $advantage): static
    {
        $this->advantages->removeElement($advantage);

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

    /**
     * @return Collection<int, StaffApplication>
     */
    public function getStaffApplications(): Collection
    {
        return $this->staffApplications;
    }

    public function addStaffApplication(StaffApplication $staffApplication): static
    {
        if (!$this->staffApplications->contains($staffApplication)) {
            $this->staffApplications->add($staffApplication);
            $staffApplication->setJob($this);
        }

        return $this;
    }

    public function removeStaffApplication(StaffApplication $staffApplication): static
    {
        if ($this->staffApplications->removeElement($staffApplication)) {
            // set the owning side to null (unless already changed)
            if ($staffApplication->getJob() === $this) {
                $staffApplication->setJob(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->title;
    }

    /**
     * @return Collection<int, JobApplication>
     */
    public function getJobApplications(): Collection
    {
        return $this->jobApplications;
    }

    public function addJobApplication(JobApplication $jobApplication): static
    {
        if (!$this->jobApplications->contains($jobApplication)) {
            $this->jobApplications->add($jobApplication);
            $jobApplication->setJob($this);
        }

        return $this;
    }

    public function removeJobApplication(JobApplication $jobApplication): static
    {
        if ($this->jobApplications->removeElement($jobApplication)) {
            // set the owning side to null (unless already changed)
            if ($jobApplication->getJob() === $this) {
                $jobApplication->setJob(null);
            }
        }

        return $this;
    }

    public function getTheme(): ?Themes
    {
        return $this->theme;
    }

    public function setTheme(?Themes $theme): static
    {
        $this->theme = $theme;

        return $this;
    }
    
}
