<?php

namespace App\Form\Input;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ExerciseInput
 */
class ExerciseInput
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $title;

    /**
     * @var int|null
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 5
     * )
     */
    private $difficultyLevel;

    /**
     * @var int|null
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 60
     * )
     */
    private $minutes;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getDifficultyLevel(): ?int
    {
        return $this->difficultyLevel;
    }

    /**
     * @param int $difficultyLevel
     */
    public function setDifficultyLevel(int $difficultyLevel): void
    {
        $this->difficultyLevel = $difficultyLevel;
    }

    /**
     * @return int
     */
    public function getMinutes(): ?int
    {
        return $this->minutes;
    }

    /**
     * @param int $minutes
     */
    public function setMinutes(int $minutes): void
    {
        $this->minutes = $minutes;
    }
}
