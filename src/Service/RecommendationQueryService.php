<?php

namespace App\Service;

use App\Entity\Recommendation;
use App\Entity\User;
use App\Entity\Workout;
use App\Repository\RecommendationRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RecommendationQueryService
 * @package App\Service
 */
class RecommendationQueryService
{
    /**
     * @var RecommendationRepository
     */
    private $recommendationRepository;

    /**
     * WorkoutQueryService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->recommendationRepository = $entityManager->getRepository(Recommendation::class);
    }

    /**
     * @param User $user
     * @param \DateTimeInterface $dateTime
     * @return int
     */
    public function countByUserIdAndDate(User $user, \DateTimeInterface $dateTime): int
    {
        $recommendations = $this->recommendationRepository->findByUserAndDate($user, $dateTime);
        return count($recommendations);
    }

    /**
     * @param User $user
     * @param Workout $workout
     * @return Recommendation|null
     */
    public function findByUserAndWorkout(User $user, Workout $workout): ?Recommendation
    {
        return $this->recommendationRepository->findOneBy([
            'user_id' => $user->getId(),
            'workout_id' => $workout->getId(),
        ]);
    }
}
