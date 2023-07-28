<?php

namespace App\Entity;

use App\Repository\FwsCookieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FwsCookieRepository::class)]
class FwsCookie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $value = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,nullable: true)]
    private ?\DateTimeInterface $expire = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $path = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $domaine = null;

    #[ORM\Column(nullable: true)]
    private ?bool $secure = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getExpire(): ?\DateTimeInterface
    {
        return $this->expire;
    }

    public function setExpire(\DateTimeInterface $expire): static
    {
        $this->expire = $expire;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function isSecure(): ?bool
    {
        return $this->secure;
    }

    public function setSecure(?bool $secure): static
    {
        $this->secure = $secure;

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
}
