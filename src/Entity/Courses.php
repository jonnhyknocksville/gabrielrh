<?php

namespace App\Entity;

use App\Repository\CoursesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(length: 255)]
    private ?string $videoIntroduction = null;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Mission::class)]
    private Collection $missions;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'courses')]
    private Collection $users;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getVideoIntroduction(): ?string
    {
        return $this->videoIntroduction;
    }

    public function setVideoIntroduction(string $videoIntroduction): static
    {
        $this->videoIntroduction = $videoIntroduction;

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
            $mission->setCourse($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getCourse() === $this) {
                $mission->setCourse(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->title;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addCourse($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeCourse($this);
        }

        return $this;
    }
}
