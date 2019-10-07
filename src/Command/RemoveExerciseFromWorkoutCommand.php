<?php

namespace App\Command;

/**
 * Class RemoveExerciseFromWorkoutCommand
 * @package App\Command
 */
class RemoveExerciseFromWorkoutCommand
{
    /**
     * @var int
     */
    private $exerciseId;

    /**
     * @var int
     */
    private $workoutId;

    /**
     * RemoveExerciseFromWorkoutCommand constructor.
     * @param int $exerciseId
     * @param int $workoutId
     */
    public function __construct(int $exerciseId, int $workoutId)
    {
        $this->exerciseId = $exerciseId;
        $this->workoutId = $workoutId;
    }

    /**
     * @return int
     */
    public function getExerciseId(): int
    {
        return $this->exerciseId;
    }

    /**
     * @return int
     */
    public function getWorkoutId(): int
    {
        return $this->workoutId;
    }
}
