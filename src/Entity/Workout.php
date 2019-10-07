<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkoutRepository")
 */
class Workout
{
    use TimestampableEntity;

    public const WORKOUT_TIME_LIMIT_IN_MINUTES = 60;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="workouts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Exercise", mappedBy="workouts")
     */
    private $exercises;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recommendation", mappedBy="workout", orphanRemoval=true)
     */
    private $recommendations;

    /**
     * Workout constructor.
     */
    public function __construct()
    {
        $this->exercises = new ArrayCollection();
        $this->recommendations = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'difficultyLevel' => $this->getDifficultyLevel(),
            'isPopular' => $this->isPopular(),
            'minutes' => $this->getMinutes(),
            'title' => $this->getTitle(),
            'user' => $this->getUser()->getUsername(),
            'exercises' => $this->getExercisesArray(),
        ];
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection|Exercise[]
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    /**
     * @return Exercise[]
     */
    public function getExercisesArray(): array
    {
        $exercises = [];
        foreach ($this->getExercises() as $exercise) {
            $exercises[] = $exercise->toArray();
        }
        return $exercises;
    }

    /**
     * @param Exercise $exercise
     * @return Workout
     */
    public function addExercise(Exercise $exercise): self
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises[] = $exercise;
            $exercise->addWorkout($this);
        }

        return $this;
    }

    /**
     * @param Exercise $exercise
     * @return Workout
     */
    public function removeExercise(Exercise $exercise): self
    {
        if ($this->exercises->contains($exercise)) {
            $this->exercises->removeElement($exercise);
            $exercise->removeWorkout($this);
        }

        return $this;
    }

    /**
     * @return Collection|Recommendation[]
     */
    public function getRecommendations(): Collection
    {
        return $this->recommendations;
    }

    /**
     * @param Recommendation $recommendation
     * @return Workout
     */
    public function addRecommendation(Recommendation $recommendation): self
    {
        if (!$this->recommendations->contains($recommendation)) {
            $this->recommendations[] = $recommendation;
            $recommendation->setWorkout($this);
        }

        return $this;
    }

    /**
     * @param Recommendation $recommendation
     * @return Workout
     */
    public function removeRecommendation(Recommendation $recommendation): self
    {
        if ($this->recommendations->contains($recommendation)) {
            $this->recommendations->removeElement($recommendation);
            // set the owning side to null (unless already changed)
            if ($recommendation->getWorkout() === $this) {
                $recommendation->setWorkout(null);
            }
        }

        return $this;
    }

    /**
     * @return float
     */
    public function getDifficultyLevel(): float
    {
        $difficultySum = 0;
        foreach ($this->getExercises() as $exercise) {
            $difficultySum += $exercise->getDifficultyLevel();
        }

        $counter = $this->getExercises()->count();
        if ($counter === 0) {
            return 0;
        }
        return $difficultySum / $counter;
    }

    /**
     * @return int
     */
    public function getMinutes(): int
    {
        $minutesSum = 0;
        foreach ($this->getExercises() as $exercise) {
            $minutesSum += $exercise->getMinutes();
        }
        return $minutesSum;
    }

    /**
     * @return bool
     */
    public function isPopular(): bool
    {
        return $this->getRecommendations()->count() > 2;
    }
}
