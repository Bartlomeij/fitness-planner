<?php

namespace App\Handler\Command;

use App\Command\AddExerciseToWorkoutCommand;
use App\Command\CreateWorkoutCommand;
use App\Entity\Exercise;
use App\Entity\User;
use App\Entity\Workout;
use App\Service\ExerciseQueryService;
use App\Service\UserQueryService;
use App\Service\WorkoutQueryService;
use App\Service\WorkoutService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class AddExerciseToWorkoutCommandHandler
 * @package App\Handler\Command
 */
class AddExerciseToWorkoutCommandHandler implements MessageHandlerInterface
{
    /**
     * @var WorkoutService
     */
    private $workoutService;

    /**
     * @var ExerciseQueryService
     */
    private $exerciseQueryService;

    /**
     * @var WorkoutQueryService
     */
    private $workoutQueryService;

    /**
     * AddExerciseToWorkoutCommandHandler constructor.
     * @param WorkoutService $workoutService
     * @param ExerciseQueryService $exerciseQueryService
     * @param WorkoutQueryService $workoutQueryService
     */
    public function __construct(
        WorkoutService $workoutService,
        ExerciseQueryService $exerciseQueryService,
        WorkoutQueryService $workoutQueryService
    ) {
        $this->workoutService = $workoutService;
        $this->exerciseQueryService = $exerciseQueryService;
        $this->workoutQueryService = $workoutQueryService;
    }

    /**
     * @param AddExerciseToWorkoutCommand $command
     * @throws EntityNotFoundException
     */
    public function __invoke(AddExerciseToWorkoutCommand $command): void
    {
        $exercise = $this->exerciseQueryService->findExerciseById($command->getExerciseId());
        if (!$exercise instanceof Exercise) {
            throw new EntityNotFoundException('Entity #' . $command->getExerciseId() . ' not found');
        }

        $workout = $this->workoutQueryService->findWorkoutById($command->getWorkoutId());
        if (!$workout instanceof Workout) {
            throw new EntityNotFoundException('Entity #' . $command->getWorkoutId() . ' not found');
        }

        $this->workoutService->addExerciseToWorkout(
            $exercise,
            $workout
        );
    }
}
