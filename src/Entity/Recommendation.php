<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecommendationRepository")
 */
class Recommendation
{
    use TimestampableEntity;

    public const RECOMMENDATION_LIMIT_PER_DAY = 3;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Workout", inversedBy="recommendations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workout;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recommendations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkout(): ?Workout
    {
        return $this->workout;
    }

    public function setWorkout(?Workout $workout): self
    {
        $this->workout = $workout;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
    }
}
