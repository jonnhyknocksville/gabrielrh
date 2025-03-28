<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Exchange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[ORM\JoinColumn(nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: PersonInCharge::class, inversedBy: 'exchanges')]
    #[ORM\JoinColumn(nullable: true)]
    private ?PersonInCharge $personInCharge = null;

    #[ORM\ManyToOne(targetEntity: EmailTemplate::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EmailTemplate $emailTemplate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getPersonInCharge(): ?PersonInCharge
    {
        return $this->personInCharge;
    }

    public function setPersonInCharge(?PersonInCharge $personInCharge): self
    {
        $this->personInCharge = $personInCharge;
        return $this;
    }

    public function getEmailTemplate(): ?EmailTemplate
    {
        return $this->emailTemplate;
    }

    public function setEmailTemplate(?EmailTemplate $emailTemplate): self
    {
        $this->emailTemplate = $emailTemplate;
        return $this;
    }

}