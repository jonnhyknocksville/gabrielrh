<?php

namespace App\Entity;

use App\Repository\JobsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: JobsRepository::class)]
class Jobs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(
        message: 'Le titre ne peut pas être vide.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotBlank(
        message: 'Le salaire ne peut pas être vide.',
    )]
    #[ORM\Column]
    private ?int $salary = null;

    #[Assert\NotBlank(
        message: 'Publié le ne peut pas être vide.',
    )]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $published_on = null;

    #[Assert\NotBlank(
        message: 'Mise à jour le ne peut pas être vide.',
    )]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $updated_on = null;

    #[Assert\NotBlank(
        message: 'La description ne peut pas être vide.',
    )]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Votre description doit avoir un minimum de {{ limit }} caracteres',
        maxMessage: 'Votre description doit avoir un maximum de {{ limit }} caracteres',
    )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Assert\NotBlank(
        message: 'La date de début ne peut pas être vide.',
    )]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[Assert\NotBlank(
        message: 'La date de fin ne peut pas être vide.',
    )]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $end_date = null;

    #[Assert\NotBlank(
        message: 'L\'horaire ne peut pas être vide.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $schedule = null;

    #[Assert\NotBlank(
        message: 'L\'entreprise ne peut pas être vide.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $company = null;

    #[Assert\NotBlank(
        message: 'l\'expérience demandé ne peut pas être vide.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $experience = null;

    #[Assert\NotBlank(
        message: 'l\'adresse ne peut pas être vide.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[Assert\NotBlank(
        message: 'le code postal ne peut pas être vide.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $postal_code = null;

    #[Assert\NotBlank(
        message: 'la ville ne peut pas être vide.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[Assert\NotBlank(
        message: 'le type d\élèves ne peut pas être vide.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $type_of_audience = null;

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

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getPublishedOn(): ?\DateTimeInterface
    {
        return $this->published_on;
    }

    public function setPublishedOn(\DateTimeInterface $published_on): static
    {
        $this->published_on = $published_on;

        return $this;
    }

    public function getUpdatedOn(): ?\DateTimeInterface
    {
        return $this->updated_on;
    }

    public function setUpdatedOn(\DateTimeInterface $updated_on): static
    {
        $this->updated_on = $updated_on;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

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

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(string $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): static
    {
        $this->postal_code = $postal_code;

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

    public function getTypeOfAudience(): ?string
    {
        return $this->type_of_audience;
    }

    public function setTypeOfAudience(string $type_of_audience): static
    {
        $this->type_of_audience = $type_of_audience;

        return $this;
    }
}
