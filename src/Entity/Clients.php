<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
class Clients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $postalCode = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Mission::class)]
    private Collection $missions;

    #[ORM\Column(length: 255)]
    private ?string $personInCharge = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $backgroundColor = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Students::class)]
    private Collection $students;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Tarification::class)]
    private Collection $tarifications;

    #[ORM\Column(length: 255)]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    private ?string $commercialName = null;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->tarifications = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

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

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

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
            $mission->setClient($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getClient() === $this) {
                $mission->setClient(null);
            }
        }

        return $this;
    }

    public function getPersonInCharge(): ?string
    {
        return $this->personInCharge;
    }

    public function setPersonInCharge(string $personInCharge): static
    {
        $this->personInCharge = $personInCharge;

        return $this;
    }

    public function __toString(){
        return $this->name . " " . $this->city . "  " . $this->commercialName;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): static
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * @return Collection<int, Students>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Students $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setClient($this);
        }

        return $this;
    }

    public function removeStudent(Students $student): static
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getClient() === $this) {
                $student->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tarification>
     */
    public function getTarifications(): Collection
    {
        return $this->tarifications;
    }

    public function addTarification(Tarification $tarification): static
    {
        if (!$this->tarifications->contains($tarification)) {
            $this->tarifications->add($tarification);
            $tarification->setClient($this);
        }

        return $this;
    }

    public function removeTarification(Tarification $tarification): static
    {
        if ($this->tarifications->removeElement($tarification)) {
            // set the owning side to null (unless already changed)
            if ($tarification->getClient() === $this) {
                $tarification->setClient(null);
            }
        }

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getCommercialName(): ?string
    {
        return $this->commercialName;
    }

    public function setCommercialName(string $commercialName): static
    {
        $this->commercialName = $commercialName;

        return $this;
    }
}
