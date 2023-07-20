<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(
        message: 'Le prenom ne peut pas être vide.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[Assert\NotBlank(
        message: 'Le nom ne peut pas être vide.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank(
        message: 'L\email ne peut pas être vide.',
    )]
    #[Assert\Email(
        message: 'L\'email {{ value }} n\'est pas valide.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[Assert\NotBlank(
        message: 'Le téléphone ne peut pas être vide.',
    )]
    #[Assert\Regex('^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$^',
    message : "Veuillez renseigner un numéro de téléphone français")]
    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[Assert\NotBlank(
        message: 'L\'objet ne peut pas être vide.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $Object = null;

    #[Assert\NotBlank(
        message: 'Le message ne peut pas être vide.',
    )]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Votre message doit avoir un minimum de {{ limit }} caracteres',
        maxMessage: 'Votre message doit avoir un maximum de {{ limit }} caracteres',
    )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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

    public function getObject(): ?string
    {
        return $this->Object;
    }

    public function setObject(string $Object): static
    {
        $this->Object = $Object;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
