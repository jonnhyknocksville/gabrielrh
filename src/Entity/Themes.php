<?php

namespace App\Entity;

use App\Repository\ThemesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemesRepository::class)]
class Themes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $longDescription = null;

    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: Courses::class)]
    private Collection $courses;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: ProfessionalsNeeds::class)]
    private Collection $professionalsNeeds;

    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: Jobs::class)]
    private Collection $jobs;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->professionalsNeeds = new ArrayCollection();
        $this->jobs = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): static
    {
        $this->longDescription = $longDescription;

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
            $course->setTheme($this);
        }

        return $this;
    }

    public function removeCourse(Courses $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getTheme() === $this) {
                $course->setTheme(null);
            }
        }

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function __toString(){
        return $this->title;
    }

    /**
     * @return Collection<int, ProfessionalsNeeds>
     */
    public function getProfessionalsNeeds(): Collection
    {
        return $this->professionalsNeeds;
    }

    public function addProfessionalsNeed(ProfessionalsNeeds $professionalsNeed): static
    {
        if (!$this->professionalsNeeds->contains($professionalsNeed)) {
            $this->professionalsNeeds->add($professionalsNeed);
            $professionalsNeed->setTheme($this);
        }

        return $this;
    }

    public function removeProfessionalsNeed(ProfessionalsNeeds $professionalsNeed): static
    {
        if ($this->professionalsNeeds->removeElement($professionalsNeed)) {
            // set the owning side to null (unless already changed)
            if ($professionalsNeed->getTheme() === $this) {
                $professionalsNeed->setTheme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Jobs>
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Jobs $job): static
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
            $job->setTheme($this);
        }

        return $this;
    }

    public function removeJob(Jobs $job): static
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getTheme() === $this) {
                $job->setTheme(null);
            }
        }

        return $this;
    }
}
