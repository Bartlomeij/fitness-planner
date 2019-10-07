<?php

namespace App\Entity\Factory;

use App\Entity\Exercise;
use App\Entity\User;

/**
 * Class ExerciseFactory
 * @package App\Entity\Factory
 */
class ExerciseFactory
{
    /**
     * @param string $title
     * @param int $difficultyLevel
     * @param int $minutes
     * @param User $user
     * @return Exercise
     */
    public static function createNewExercise(
        string $title,
        int $difficultyLevel,
        int $minutes,
        User $user
    ): Exercise {
        $exercise = new Exercise();
        $exercise->setTitle($title);
        $exercise->setMinutes($minutes);
        $exercise->setDifficultyLevel($difficultyLevel);
        $exercise->setUser($user);
        return $exercise;
    }
}
