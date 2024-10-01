<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Mission::class)]
    private Collection $missions;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\ManyToMany(targetEntity: Courses::class, inversedBy: 'users')]
    private Collection $courses;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $kbis = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $kbisUpdatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $identity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $diplomas = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $diplomasUpdatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cv = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $cvUpdatedAt = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: StaffApplication::class)]
    private Collection $staffApplications;

    #[ORM\Column(length: 14)]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $legalForm = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $teacher = null;

    #[ORM\Column(length: 10)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $naf = null;

    #[ORM\Column(length: 255)]
    private ?string $iban = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $criminalRecord = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $criminalRecordUpdatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attestationVigilance = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $attestationVigilanceUpdatedAt = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Contract::class)]
    private Collection $contracts;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->staffApplications = new ArrayCollection();
        $this->contracts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

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
            $mission->setUser($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getUser() === $this) {
                $mission->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return strtoupper($this->email); //or anything else
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @return Collection<int, Courses>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Courses $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
        }

        return $this;
    }

    public function removeCourse(Courses $course): static
    {
        $this->courses->removeElement($course);

        return $this;
    }

    public function getKbis(): ?string
    {
        return $this->kbis;
    }

    public function setKbis(?string $kbis): self
    {
        $this->kbis = $kbis;
        return $this;
    }

    public function getKbisUpdatedAt(): ?\DateTimeInterface
    {
        return $this->kbisUpdatedAt;
    }

    public function getIdentity(): ?string
    {
        return $this->identity;
    }

    public function setIdentity(string $identity): static
    {
        $this->identity = $identity;

        return $this;
    }

    public function getDiplomas(): ?string
    {
        return $this->diplomas;
    }

    public function setDiplomas(?string $diplomas): self
    {
        $this->diplomas = $diplomas;
        return $this;
    }

    public function getDiplomasUpdatedAt(): ?\DateTimeInterface
    {
        return $this->diplomasUpdatedAt;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;
        return $this;
    }

    public function getCvUpdatedAt(): ?\DateTimeInterface
    {
        return $this->cvUpdatedAt;
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
            $staffApplication->setUser($this);
        }

        return $this;
    }

    public function removeStaffApplication(StaffApplication $staffApplication): static
    {
        if ($this->staffApplications->removeElement($staffApplication)) {
            // set the owning side to null (unless already changed)
            if ($staffApplication->getUser() === $this) {
                $staffApplication->setUser(null);
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getLegalForm(): ?string
    {
        return $this->legalForm;
    }

    public function setLegalForm(?string $legalForm): static
    {
        $this->legalForm = $legalForm;

        return $this;
    }

    public function getTeacher(): ?string
    {
        return $this->teacher;
    }

    public function setTeacher(?string $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
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

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

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

    public function getNaf(): ?string
    {
        return $this->naf;
    }

    public function setNaf(string $naf): static
    {
        $this->naf = $naf;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): static
    {
        $this->iban = $iban;

        return $this;
    }

    public function getCriminalRecord(): ?string
    {
        return $this->criminalRecord;
    }

    public function setCriminalRecord(?string $criminalRecord): self
    {
        $this->criminalRecord = $criminalRecord;
        return $this;
    }

    public function getCriminalRecordUpdatedAt(): ?\DateTimeInterface
    {
        return $this->criminalRecordUpdatedAt;
    }

    public function getAttestationVigilance(): ?string
    {
        return $this->attestationVigilance;
    }

    public function setAttestationVigilance(?string $attestationVigilance): self
    {
        $this->attestationVigilance = $attestationVigilance;
        return $this;
    }

    public function getAttestationVigilanceUpdatedAt(): ?\DateTimeInterface
    {
        return $this->attestationVigilanceUpdatedAt;
    }


    public function setKbisUpdatedAt(?\DateTimeInterface $kbisUpdatedAt): static
    {
        $this->kbisUpdatedAt = $kbisUpdatedAt;

        return $this;
    }

    public function setCvUpdatedAt(?\DateTimeInterface $cvUpdatedAt): static
    {
        $this->cvUpdatedAt = $cvUpdatedAt;

        return $this;
    }

    public function setDiplomasUpdatedAt(?\DateTimeInterface $diplomasUpdatedAt): static
    {
        $this->diplomasUpdatedAt = $diplomasUpdatedAt;

        return $this;
    }
    public function setCriminalRecordUpdatedAt(?\DateTimeInterface $criminalRecordUpdatedAt): static
    {
        $this->criminalRecordUpdatedAt = $criminalRecordUpdatedAt;

        return $this;
    }

    public function setAttestationVigilanceUpdatedAt(?\DateTimeInterface $attestationVigilanceUpdatedAt): static
    {
        $this->attestationVigilanceUpdatedAt = $attestationVigilanceUpdatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Contract>
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function addContract(Contract $contract): static
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts->add($contract);
            $contract->setUser($this);
        }

        return $this;
    }

    public function removeContract(Contract $contract): static
    {
        if ($this->contracts->removeElement($contract)) {
            // set the owning side to null (unless already changed)
            if ($contract->getUser() === $this) {
                $contract->setUser(null);
            }
        }

        return $this;
    }
}