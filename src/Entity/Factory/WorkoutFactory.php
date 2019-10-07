<?php

namespace App\Entity\Factory;

use App\Entity\User;
use App\Entity\Workout;

/**
 * Class WorkoutFactory
 * @package App\Entity\Factory
 */
class WorkoutFactory
{
    /**
     * @param string $title
     * @param User $user
     * @return Workout
     */
    public static function createNewWorkout(
        string $title,
        User $user
    ): Workout {
        $workout = new Workout();
        $workout->setTitle($title);
        $workout->setUser($user);
        return $workout;
    }
}
