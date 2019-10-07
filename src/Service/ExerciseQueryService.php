<?php

namespace App\Service;

use App\Entity\Exercise;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ExerciseQueryService
 * @package App\Service
 */
class ExerciseQueryService
{
    /**
     * @var ExerciseRepository
     */
    private $exerciseRepository;

    /**
     * ExerciseQueryService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->exerciseRepository = $entityManager->getRepository(Exercise::class);
    }

    /**
     * @param int $id
     * @return Exercise|null
     */
    public function findExerciseById(int $id): ?Exercise
    {
        return $this->exerciseRepository->find($id);
    }

    /**
     * @return array
     */
    public function getAllExercisesArray(): array
    {
        $exercisesArray = [];
        foreach ($this->exerciseRepository->findAll() as $exercise) {
            $exercisesArray[] = $exercise->toArray();
        }
        return $exercisesArray;
    }
}
