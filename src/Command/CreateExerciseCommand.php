<?php

namespace App\Command;

/**
 * Class CreateExerciseCommand
 * @package App\Command
 */
class CreateExerciseCommand
{
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
     * @var int
     */
    private $userId;

    /**
     * CreateExerciseCommand constructor.
     * @param string $title
     * @param int $difficultyLevel
     * @param int $minutes
     * @param int $userId
     */
    public function __construct(string $title, int $difficultyLevel, int $minutes, int $userId)
    {
        $this->title = $title;
        $this->difficultyLevel = $difficultyLevel;
        $this->minutes = $minutes;
        $this->userId = $userId;
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

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
