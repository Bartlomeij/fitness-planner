<?php

namespace App\Entity\Factory;

use App\Entity\Recommendation;
use App\Entity\User;
use App\Entity\Workout;

/**
 * Class RecommendationFactory
 * @package App\Entity\Factory
 */
class RecommendationFactory
{
    /**
     * @param User $user
     * @param Workout $workout
     * @return Recommendation
     */
    public static function createNewRecommendation(
        User $user,
        Workout $workout
    ): Recommendation {
        $recommendation = new Recommendation();
        $recommendation->setWorkout($workout);
        $recommendation->setUser($user);
        return $recommendation;
    }
}
