<?php

namespace App\Entity;

use App\Repository\StudentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentsRepository::class)]
class Students
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $student = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    private ?Clients $client = null;

    #[ORM\OneToMany(mappedBy: 'student', targetEntity: Mission::class)]
    private Collection $missions;

    #[ORM\Column(length: 255)]
    private ?string $hourlyPrice = null;

    #[ORM\Column(length: 255)]
    private ?string $dailyPrice = null;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?string
    {
        return $this->student;
    }

    public function setStudent(string $student): static
    {
        $this->student = $student;

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

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): static
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->setStudent($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getStudent() === $this) {
                $mission->setStudent(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return  strtoupper($this->student . ' - ' . $this->client->getName() . ' - ' . $this->getHourlyPrice()); //or anything else
      }

    public function getHourlyPrice(): ?string
    {
        return $this->hourlyPrice;
    }

    public function setHourlyPrice(string $hourlyPrice): static
    {
        $this->hourlyPrice = $hourlyPrice;

        return $this;
    }

    public function getDailyPrice(): ?string
    {
        return $this->dailyPrice;
    }

    public function setDailyPrice(string $dailyPrice): static
    {
        $this->dailyPrice = $dailyPrice;

        return $this;
    }
}
