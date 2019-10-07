<?php

namespace App\Service;

use App\Entity\Factory\UserFactory;
use App\Entity\Factory\WorkoutFactory;
use App\Entity\User;
use App\Entity\Workout;
use App\Exception\UserAlreadyExistsException;
use App\Repository\WorkoutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class WorkoutQueryService
 * @package App\Service
 */
class WorkoutQueryService
{
    /**
     * @var WorkoutRepository
     */
    private $workoutRepository;

    /**
     * WorkoutQueryService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->workoutRepository = $entityManager->getRepository(Workout::class);
    }

    /**
     * @param int $id
     * @return Workout|null
     */
    public function findWorkoutById(int $id): ?Workout
    {
        return $this->workoutRepository->find($id);
    }

    /**
     * @return array
     */
    public function getAllWorkoutsArray(): array
    {
        $workoutsArray = [];
        foreach ($this->workoutRepository->findAll() as $workout) {
            $workoutsArray[] = $workout->toArray();
        }
        return $workoutsArray;
    }
}
