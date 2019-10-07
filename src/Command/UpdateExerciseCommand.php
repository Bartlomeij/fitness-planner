<?php

namespace App\Command;

/**
 * Class UpdateExerciseCommand
 * @package App\Command
 */
class UpdateExerciseCommand
{
    /**
     * @var int
     */
    private $exerciseId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $difficultyLevel;

    /**
     * @var int
     */
    private $minutes;

    /**
     * UpdateExerciseCommand constructor.
     * @param int $exerciseId
     * @param string $title
     * @param int $difficultyLevel
     * @param int $minutes
     */
    public function __construct(int $exerciseId, string $title, int $difficultyLevel, int $minutes)
    {
        $this->exerciseId = $exerciseId;
        $this->title = $title;
        $this->difficultyLevel = $difficultyLevel;
        $this->minutes = $minutes;
    }

    /**
     * @return int
     */
    public function getExerciseId(): int
    {
        return $this->exerciseId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getDifficultyLevel(): int
    {
        return $this->difficultyLevel;
    }

    /**
     * @return int
     */
    public function getMinutes(): int
    {
        return $this->minutes;
    }
}
