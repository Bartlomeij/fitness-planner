<?php

namespace App\Event;

/**
 * Class WorkoutRecommendedEvent
 * @package App\Event
 */
class WorkoutRecommendedEvent
{
    /**
     * @var int
     */
    private $workoutId;

    /**
     * @var int
     */
    private $userId;

    /**
     * WorkoutRecommendedEvent constructor.
     * @param int $workoutId
     * @param int $userId
     */
    public function __construct(int $workoutId, int $userId)
    {
        $this->workoutId = $workoutId;
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getWorkoutId(): int
    {
        return $this->workoutId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
