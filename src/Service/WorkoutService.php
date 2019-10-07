<?php

namespace App\Service;

use App\Entity\Exercise;
use App\Entity\Factory\RecommendationFactory;
use App\Entity\Factory\WorkoutFactory;
use App\Entity\User;
use App\Entity\Workout;
use App\Event\WorkoutRecommendedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class WorkoutService
 * @package App\Service
 */
class WorkoutService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MessageBusInterface
     */
    private $eventBus;

    /**
     * WorkoutService constructor.
     * @param EntityManagerInterface $entityManager
     * @param MessageBusInterface $eventBus
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        MessageBusInterface $eventBus
    ) {
        $this->entityManager = $entityManager;
        $this->eventBus = $eventBus;
    }

    /**
     * @param string $title
     * @param User $user
     */
    public function createWorkout(string $title, User $user): void
    {
        $workout = WorkoutFactory::createNewWorkout(
            $title,
            $user
        );
        $this->entityManager->persist($workout);
        $this->entityManager->flush();
    }

    /**
     * @param int $workoutId
     * @throws EntityNotFoundException
     */
    public function deleteWorkout(int $workoutId): void
    {
        $workout = $this->entityManager->getRepository(Workout::class)->find($workoutId);
        if (!$workout instanceof Workout) {
            throw new EntityNotFoundException('Entity #' . $workoutId . ' not found');
        }

        $this->entityManager->remove($workout);
        $this->entityManager->flush();
    }

    /**
     * @param int $workoutId
     * @param string $title
     * @throws EntityNotFoundException
     */
    public function updateWorkout(int $workoutId, string $title): void
    {
        $workout = $this->entityManager->getRepository(Workout::class)->find($workoutId);
        if (!$workout instanceof Workout) {
            throw new EntityNotFoundException('Entity #' . $workoutId . ' not found');
        }

        $workout->setTitle($title);
        $this->entityManager->persist($workout);
        $this->entityManager->flush();
    }

    /**
     * @param Exercise $exercise
     * @param Workout $workout
     */
    public function addExerciseToWorkout(Exercise $exercise, Workout $workout): void
    {
        $workout->addExercise($exercise);

        $this->entityManager->persist($workout);
        $this->entityManager->flush();
    }

    /**
     * @param Exercise $exercise
     * @param Workout $workout
     */
    public function removeExerciseFromWorkout(Exercise $exercise, Workout $workout): void
    {
        $workout->removeExercise($exercise);

        $this->entityManager->persist($workout);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     * @param Workout $workout
     */
    public function recommendWorkout(User $user, Workout $workout): void
    {
        $recommendation = RecommendationFactory::createNewRecommendation(
            $user,
            $workout
        );

        $this->entityManager->persist($recommendation);
        $this->entityManager->flush();

        $this->eventBus->dispatch(
            new WorkoutRecommendedEvent($workout->getId(), $user->getId())
        );
    }
}
