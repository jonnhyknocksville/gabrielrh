<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
class PersonInCharge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $lastName = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $firstName = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $school = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $position = null;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^\+?[0-9]{7,15}$/", message: "Invalid phone number")]
    private ?string $phoneNumber = null;

    #[ORM\OneToMany(mappedBy: 'personInCharge', targetEntity: Exchange::class, orphanRemoval: true)]
    private Collection $exchanges;

    #[ORM\ManyToMany(targetEntity: ListContact::class, inversedBy: 'persons')]
    #[ORM\JoinTable(name: 'person_list_contact')]
    private Collection $lists;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $source = null;

    /**
     * @var Collection<int, Courses>
     */
    #[ORM\ManyToMany(targetEntity: Courses::class, inversedBy: 'personInCharges')]
    private Collection $courses;

    #[ORM\Column(nullable: true)]
    private ?bool $collaboration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $collaborationSubject = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Choice(choices: ['Active', 'Deactivated', 'In collaboration', 'Contact'], message: "Choisissez un statut valide.")]
    private ?string $status = null;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'copiedIn')]
    #[ORM\JoinTable(name: 'person_in_charge_cc')]
    private Collection $ccContacts;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact = null;

    public function __construct()
    {
        $this->exchanges = new ArrayCollection();
        $this->lists = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->ccContacts = new ArrayCollection();
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(string $school): self
    {
        $this->school = $school;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getExchanges(): Collection
    {
        return $this->exchanges;
    }

    public function addExchange(Exchange $exchange): self
    {
        if (!$this->exchanges->contains($exchange)) {
            $this->exchanges->add($exchange);
            $exchange->setPersonInCharge($this);
        }

        return $this;
    }

    public function removeExchange(Exchange $exchange): self
    {
        if ($this->exchanges->removeElement($exchange)) {
            if ($exchange->getPersonInCharge() === $this) {
                $exchange->setPersonInCharge(null);
            }
        }

        return $this;
    }

    public function getLists(): Collection
    {
        return $this->lists;
    }

    public function addList(ListContact $list): self
    {
        if (!$this->lists->contains($list)) {
            $this->lists->add($list);
            $list->addPerson($this);
        }

        return $this;
    }

    public function removeList(ListContact $list): self
    {
        if ($this->lists->removeElement($list)) {
            $list->removePerson($this);
        }

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): static
    {
        $this->source = $source;

        return $this;
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

    public function isCollaboration(): ?bool
    {
        return $this->collaboration;
    }

    public function setCollaboration(bool $collaboration): static
    {
        $this->collaboration = $collaboration;

        return $this;
    }

    public function getCollaborationSubject(): ?string
    {
        return $this->collaborationSubject;
    }

    public function setCollaborationSubject(?string $collaborationSubject): static
    {
        $this->collaborationSubject = $collaborationSubject;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function hasRecentExchange(): bool
    {
        $oneMonthAgo = new \DateTime('-1 month');

        foreach ($this->exchanges as $exchange) {
            if ($exchange->getDate() >= $oneMonthAgo) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection<int, PersonInCharge>
     */
    public function getCcContacts(): Collection
    {
        return $this->ccContacts;
    }

    public function addCcContact(PersonInCharge $person): static
    {
        if (!$this->ccContacts->contains($person)) {
            $this->ccContacts->add($person);
        }

        return $this;
    }

    public function removeCcContact(PersonInCharge $person): static
    {
        $this->ccContacts->removeElement($person);

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }



}
