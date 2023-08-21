<?php

namespace App\Entity;

use App\Repository\CoursesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursesRepository::class)]
class Courses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?Themes $theme = null;

    #[ORM\Column(length: 255)]
    private ?string $heading = null;

    #[ORM\Column(length: 255)]
    private ?string $titleIntroduction = null;

    #[ORM\Column]
    private array $introduction = [];

    #[ORM\Column]
    private array $objectives = [];

    #[ORM\Column]
    private array $learningPath = [];

    #[ORM\Column]
    private array $public = [];

    #[ORM\Column]
    private array $requirements = [];

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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

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

    public function getTheme(): ?Themes
    {
        return $this->theme;
    }

    public function setTheme(?Themes $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function setHeading(string $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function getTitleIntroduction(): ?string
    {
        return $this->titleIntroduction;
    }

    public function setTitleIntroduction(string $titleIntroduction): static
    {
        $this->titleIntroduction = $titleIntroduction;

        return $this;
    }

    public function getIntroduction(): array
    {
        return $this->introduction;
    }

    public function setIntroduction(array $introduction): static
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getObjectives(): array
    {
        return $this->objectives;
    }

    public function setObjectives(array $objectives): static
    {
        $this->objectives = $objectives;

        return $this;
    }

    public function getLearningPath(): array
    {
        return $this->learningPath;
    }

    public function setLearningPath(array $learningPath): static
    {
        $this->learningPath = $learningPath;

        return $this;
    }

    public function getPublic(): array
    {
        return $this->public;
    }

    public function setPublic(array $public): static
    {
        $this->public = $public;

        return $this;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function setRequirements(array $requirements): static
    {
        $this->requirements = $requirements;

        return $this;
    }
}
