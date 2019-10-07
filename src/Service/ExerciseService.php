<?php

namespace App\Service;

use App\Entity\Exercise;
use App\Entity\Factory\ExerciseFactory;
use App\Entity\User;
use App\Exception\ExerciseDeleteException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class ExerciseService
 * @package App\Service
 */
class ExerciseService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ExerciseService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $title
     * @param int $difficultyLevel
     * @param int $minutes
     * @param User $user
     */
    public function createExercise(string $title, int $difficultyLevel, int $minutes, User $user): void
    {
        $exercise = ExerciseFactory::createNewExercise(
            $title,
            $difficultyLevel,
            $minutes,
            $user
        );

        $this->entityManager->persist($exercise);
        $this->entityManager->flush();
    }

    /**
     * @param int $exerciseId
     * @throws EntityNotFoundException
     * @throws ExerciseDeleteException
     */
    public function deleteExercise(int $exerciseId): void
    {
        $exercise = $this->entityManager->getRepository(Exercise::class)->find($exerciseId);
        if (!$exercise instanceof Exercise) {
            throw new EntityNotFoundException('Entity #' . $exercise . ' not found');
        }

        if ($exercise->getWorkouts()->count() > 0) {
            throw new ExerciseDeleteException('Cannot remove exercise if is added to workout');
        }

        $this->entityManager->remove($exercise);
        $this->entityManager->flush();
    }

    /**
     * @param int $exerciseId
     * @param string $title
     * @param int $difficultyLevel
     * @param int $minutes
     * @throws EntityNotFoundException
     */
    public function updateExercise(int $exerciseId, string $title, int $difficultyLevel, int $minutes): void
    {
        $exercise = $this->entityManager->getRepository(Exercise::class)->find($exerciseId);
        if (!$exercise instanceof Exercise) {
            throw new EntityNotFoundException('Entity #' . $exercise . ' not found');
        }

        $exercise->setTitle($title);
        $exercise->setDifficultyLevel($difficultyLevel);
        $exercise->setMinutes($minutes);

        $this->entityManager->persist($exercise);
        $this->entityManager->flush();
    }
}
