<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ListContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: PersonInCharge::class, mappedBy: 'lists')]
    private Collection $persons;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function addPerson(PersonInCharge $person): self
    {
        if (!$this->persons->contains($person)) {
            $this->persons->add($person);
            $person->addList($this);
        }

        return $this;
    }

    public function removePerson(PersonInCharge $person): self
    {
        if ($this->persons->removeElement($person)) {
            $person->removeList($this);
        }

        return $this;
    }
}
