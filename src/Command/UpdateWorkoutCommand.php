<?php

namespace App\Command;

/**
 * Class UpdateWorkoutCommand
 * @package App\Command
 */
class UpdateWorkoutCommand
{
    /**
     * @var int
     */
    private $workoutId;

    /**
     * @var string
     */
    private $title;

    /**
     * UpdateWorkoutCommand constructor.
     * @param int $workoutId
     * @param string $title
     */
    public function __construct(int $workoutId, string $title)
    {
        $this->workoutId = $workoutId;
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getWorkoutId(): int
    {
        return $this->workoutId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
