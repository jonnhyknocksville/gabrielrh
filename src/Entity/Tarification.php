<?php

namespace App\Entity;

use App\Repository\TarificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarificationRepository::class)]
class Tarification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $promotion = null;

    #[ORM\ManyToOne(inversedBy: 'tarifications')]
    private ?Clients $client = null;

    #[ORM\Column(length: 255)]
    private ?string $hourlyRate = null;

    #[ORM\Column(length: 255)]
    private ?string $dailyRate = null;

    #[ORM\OneToMany(mappedBy: 'tarification', targetEntity: Mission::class)]
    private Collection $missions;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPromotion(): ?string
    {
        return $this->promotion;
    }

    public function setPromotion(string $promotion): static
    {
        $this->promotion = $promotion;

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

    public function getHourlyRate(): ?string
    {
        return $this->hourlyRate;
    }

    public function setHourlyRate(string $hourlyRate): static
    {
        $this->hourlyRate = $hourlyRate;

        return $this;
    }

    public function getDailyRate(): ?string
    {
        return $this->dailyRate;
    }

    public function setDailyRate(string $dailyRate): static
    {
        $this->dailyRate = $dailyRate;

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
            $mission->setTarification($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getTarification() === $this) {
                $mission->setTarification(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return  $this->promotion . ' - ' . $this->getClient()->getName() . ' - ' . $this->hourlyRate . ' - ' . $this->dailyRate; 
      }
}
