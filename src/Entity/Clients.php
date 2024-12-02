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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Mission::class)]
    private Collection $missions;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $personInCharge = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phonePersonInCharge = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $backgroundColor = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Students::class)]
    private Collection $students;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Tarification::class)]
    private Collection $tarifications;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commercialName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $representative = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nbrAgrement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $naf = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $legalForm = null;

    #[ORM\Column(nullable: true)]
    private ?int $socialCapital = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $missionAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $missionClient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $missionPostalCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $missionCity = null;

    #[ORM\OneToMany(mappedBy: 'invoice_client', targetEntity: Mission::class)]
    private Collection $invoice_client_missions;

    #[ORM\Column(length: 255)]
    private ?string $emailPersonInCharge = null;

    #[ORM\Column(length: 255)]
    private ?string $emailRepresentative = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phoneRepresentative = null;

    #[ORM\Column(nullable: true)]
    private ?array $emailContactToAdd = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accountantFullName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accountantEmail = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Invoices::class)]
    private Collection $invoices;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accountServiceName = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Estimate::class)]
    private Collection $estimates;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->tarifications = new ArrayCollection();
        $this->invoice_client_missions = new ArrayCollection();
        $this->invoices = new ArrayCollection();
        $this->estimates = new ArrayCollection();
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

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
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

    public function setPersonInCharge(?string $personInCharge): static
    {
        $this->personInCharge = $personInCharge;

        return $this;
    }

    public function getPhonePersonInCharge(): ?string
    {
        return $this->phonePersonInCharge;
    }

    public function setPhonePersonInCharge(?string $phonePersonInCharge): static
    {
        $this->phonePersonInCharge = $phonePersonInCharge;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): static
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

    public function setSiret(?string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getCommercialName(): ?string
    {
        return $this->commercialName;
    }

    public function setCommercialName(?string $commercialName): static
    {
        $this->commercialName = $commercialName;

        return $this;
    }

    public function getRepresentative(): ?string
    {
        return $this->representative;
    }

    public function setRepresentative(?string $representative): static
    {
        $this->representative = $representative;

        return $this;
    }

    public function getNbrAgrement(): ?string
    {
        return $this->nbrAgrement;
    }

    public function setNbrAgrement(?string $nbrAgrement): static
    {
        $this->nbrAgrement = $nbrAgrement;

        return $this;
    }

    public function getNaf(): ?string
    {
        return $this->naf;
    }

    public function setNaf(?string $naf): static
    {
        $this->naf = $naf;

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

    public function getSocialCapital(): ?int
    {
        return $this->socialCapital;
    }

    public function setSocialCapital(?int $socialCapital): static
    {
        $this->socialCapital = $socialCapital;

        return $this;
    }

    public function getMissionAddress(): ?string
    {
        return $this->missionAddress;
    }

    public function setMissionAddress(?string $missionAddress): static
    {
        $this->missionAddress = $missionAddress;

        return $this;
    }

    public function getMissionClient(): ?string
    {
        return $this->missionClient;
    }

    public function setMissionClient(?string $missionClient): static
    {
        $this->missionClient = $missionClient;

        return $this;
    }

    public function getMissionPostalCode(): ?string
    {
        return $this->missionPostalCode;
    }

    public function setMissionPostalCode(?string $missionPostalCode): static
    {
        $this->missionPostalCode = $missionPostalCode;

        return $this;
    }

    public function getMissionCity(): ?string
    {
        return $this->missionCity;
    }

    public function setMissionCity(?string $missionCity): static
    {
        $this->missionCity = $missionCity;

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getInvoiceClientMissions(): Collection
    {
        return $this->invoice_client_missions;
    }

    public function addInvoiceClientMission(Mission $invoiceClientMission): static
    {
        if (!$this->invoice_client_missions->contains($invoiceClientMission)) {
            $this->invoice_client_missions->add($invoiceClientMission);
            $invoiceClientMission->setInvoiceClient($this);
        }

        return $this;
    }

    public function removeInvoiceClientMission(Mission $invoiceClientMission): static
    {
        if ($this->invoice_client_missions->removeElement($invoiceClientMission)) {
            // set the owning side to null (unless already changed)
            if ($invoiceClientMission->getInvoiceClient() === $this) {
                $invoiceClientMission->setInvoiceClient(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return strtoupper($this->name . " - " . $this->commercialName . " - " . $this->city); //or anything else
    }

    public function getEmailPersonInCharge(): ?string
    {
        return $this->emailPersonInCharge;
    }

    public function setEmailPersonInCharge(string $emailPersonInCharge): static
    {
        $this->emailPersonInCharge = $emailPersonInCharge;

        return $this;
    }

    public function getEmailRepresentative(): ?string
    {
        return $this->emailRepresentative;
    }

    public function setEmailRepresentative(string $emailRepresentative): static
    {
        $this->emailRepresentative = $emailRepresentative;

        return $this;
    }

    public function getPhoneRepresentative(): ?string
    {
        return $this->phoneRepresentative;
    }

    public function setPhoneRepresentative(string $phoneRepresentative): static
    {
        $this->phoneRepresentative = $phoneRepresentative;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getEmailContactToAdd(): ?array
    {
        return $this->emailContactToAdd;
    }

    /**
     * @param array|null $emailContactToAdd
     * @return $this
     */
    public function setEmailContactToAdd(?array $emailContactToAdd): self
    {
        $this->emailContactToAdd = $emailContactToAdd;
        return $this;
    }

    /**
     * Ajoute un email au tableau emailContactToAdd
     * 
     * @param string $email
     * @return $this
     */
    public function addEmailContact(string $email): self
    {
        if ($this->emailContactToAdd === null) {
            $this->emailContactToAdd = [];
        }

        if (!in_array($email, $this->emailContactToAdd)) {
            $this->emailContactToAdd[] = $email;
        }

        return $this;
    }

    /**
     * Supprime un email du tableau emailContactToAdd
     * 
     * @param string $email
     * @return $this
     */
    public function removeEmailContact(string $email): self
    {
        if ($this->emailContactToAdd !== null) {
            $index = array_search($email, $this->emailContactToAdd);
            if ($index !== false) {
                unset($this->emailContactToAdd[$index]);
                $this->emailContactToAdd = array_values($this->emailContactToAdd); // Réindexe le tableau
            }
        }
        return $this;
    }

    public function getAccountantFullName(): ?string
    {
        return $this->accountantFullName;
    }

    public function setAccountantFullName(?string $accountantFullName): static
    {
        $this->accountantFullName = $accountantFullName;

        return $this;
    }

    public function getAccountantEmail(): ?string
    {
        return $this->accountantEmail;
    }

    public function setAccountantEmail(?string $accountantEmail): static
    {
        $this->accountantEmail = $accountantEmail;

        return $this;
    }

    /**
     * @return Collection<int, Invoices>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoices $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setClient($this);
        }

        return $this;
    }

    public function removeInvoice(Invoices $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getClient() === $this) {
                $invoice->setClient(null);
            }
        }

        return $this;
    }

    public function getAccountServiceName(): ?string
    {
        return $this->accountServiceName;
    }

    public function setAccountServiceName(?string $accountServiceName): static
    {
        $this->accountServiceName = $accountServiceName;

        return $this;
    }

    /**
     * @return Collection<int, Estimate>
     */
    public function getEstimates(): Collection
    {
        return $this->estimates;
    }

    public function addEstimate(Estimate $estimate): static
    {
        if (!$this->estimates->contains($estimate)) {
            $this->estimates->add($estimate);
            $estimate->setClient($this);
        }

        return $this;
    }

    public function removeEstimate(Estimate $estimate): static
    {
        if ($this->estimates->removeElement($estimate)) {
            // set the owning side to null (unless already changed)
            if ($estimate->getClient() === $this) {
                $estimate->setClient(null);
            }
        }

        return $this;
    }

}
