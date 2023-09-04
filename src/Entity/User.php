<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[Vich\UploadableField(mapping: 'kbisFile', fileNameProperty: 'kbis')]
    private ?File $kbisFile = null;

    #[ORM\Column(length: 255)]
    private ?string $kbis = null;

    #[ORM\Column(length: 255)]
    private $namerKibs;

    #[Vich\UploadableField(mapping: 'vigilanceFile', fileNameProperty: 'vigilance')]
    private ?File $vigilanceFile = null;

    #[ORM\Column(length: 255)]
    private ?string $vigilance = null;

    #[ORM\Column(length: 255)]
    private $namerVigilance;

    #[Vich\UploadableField(mapping: 'identityFile', fileNameProperty: 'identity')]
    private ?File $identityFile = null;

    #[ORM\Column(length: 255)]
    private ?string $identity = null;

    #[ORM\Column(length: 255)]
    private $namerIdentity;

    #[Vich\UploadableField(mapping: 'diplomasFile', fileNameProperty: 'diplomas')]
    private ?File $diplomasFile = null;

    #[ORM\Column(length: 255)]
    private ?string $diplomas = null;

    #[ORM\Column(length: 255)]
    private $namerDiplomas;

    #[ORM\Column(length: 255)]
    private ?string $cv = null;

    #[Vich\UploadableField(mapping: 'cvFile', fileNameProperty: 'cv')]
    private ?File $cvFile = null;

    #[ORM\Column(length: 255)]
    private $namerCV;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: StaffApplication::class)]
    private Collection $staffApplications;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->staffApplications = new ArrayCollection();
    }

    public function setCvFile(?File $imageFile = null): void
    {

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }

        $this->cvFile = $imageFile;
       
    }

    public function getCvFile(): ?File
    {
        return $this->cvFile;
    }

    public function setKbisFile(?File $imageFile = null): void
    {
        $this->kbisFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getKbisFile(): ?File
    {
        return $this->kbisFile;
    }

    public function setVigilanceFile(?File $imageFile = null): void
    {
        $this->vigilanceFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getVigilanceFile(): ?File
    {
        return $this->vigilanceFile;
    }

    public function setIdentityFile(?File $imageFile = null): void
    {
        $this->identityFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getIdentityFile(): ?File
    {
        return $this->identityFile;
    }

    public function setDiplomasFile(?File $imageFile = null): void
    {
        $this->diplomasFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getDiplomasFile(): ?File
    {
        return $this->diplomasFile;
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

    public function __toString(){
        return  strtoupper($this->email); //or anything else
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

    public function getIdentifier():string
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
    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function getKbis(): ?string
    {
        return $this->kbis;
    }

    public function setKbis(string $kbis): static
    {
        $this->kbis = $kbis;

        return $this;
    }

    public function getVigilance(): ?string
    {
        return $this->vigilance;
    }

    public function setVigilance(string $vigilance): static
    {
        $this->vigilance = $vigilance;

        return $this;
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

    public function setDiplomas(string $diplomas): static
    {
        $this->diplomas = $diplomas;

        return $this;
    }

    public function getNamerCV(): ?string
    {
        return $this->namerCV;
    }
    
    

    public function setNamerCV(string $namerCV): self
    {
        $this->namerCV = $namerCV;

        return $this;
    }

    public function getNamerKibs(): ?string
    {
        return $this->namerKibs;
    }

    public function setNamerKibs(string $namerKibs): self
    {
        $this->namerKibs = $namerKibs;

        return $this;
    }

    public function getNamerVigilance(): ?string
    {
        return $this->namerVigilance;
    }

    public function setNamerVigilance(string $namerVigilance): self
    {
        $this->namerVigilance = $namerVigilance;

        return $this;
    }

    public function getNamerIdentity(): ?string
    {
        return $this->namerIdentity;
    }

    public function setNamerIdentity(string $namerIdentity): self
    {
        $this->namerIdentity = $namerIdentity;

        return $this;
    }

    public function getNamerDiplomas(): ?string
    {
        return $this->namerDiplomas;
    }

    public function setNamerDiplomas(string $namerDiplomas): self
    {
        $this->namerDiplomas = $namerDiplomas;

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
}
