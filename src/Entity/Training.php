<?php

namespace App\Entity;

use App\Repository\TrainingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingRepository::class)]
class Training
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $heading = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $titleIntroduction = null;

    #[ORM\Column(nullable:true)]
    private array $introduction = [];

    #[ORM\Column(nullable:true)]
    private array $objectives = [];

    #[ORM\Column(nullable:true)]
    private array $learningPath = [];

    #[ORM\Column(nullable:true)]
    private array $public = [];

    #[ORM\Column(nullable:true)]
    private array $requirements = [];

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $videoIntroduction = null;

    #[ORM\Column]
    private ?bool $showOnWeb = null;

    #[ORM\OneToMany(mappedBy: 'training', targetEntity: Chapter::class)]
    private Collection $chapters;

    public function __construct()
    {
        $this->chapters = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function isShowOnWeb(): ?bool
    {
        return $this->showOnWeb;
    }

    public function setShowOnWeb(bool $showOnWeb): static
    {
        $this->showOnWeb = $showOnWeb;

        return $this;
    }

    /**
     * @return Collection<int, Chapter>
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): static
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters->add($chapter);
            $chapter->setTraining($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): static
    {
        if ($this->chapters->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getTraining() === $this) {
                $chapter->setTraining(null);
            }
        }

        return $this;
    }

}
