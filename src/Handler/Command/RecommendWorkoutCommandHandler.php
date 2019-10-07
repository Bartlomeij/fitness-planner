<?php

namespace App\Handler\Command;

use App\Command\AddExerciseToWorkoutCommand;
use App\Command\CreateWorkoutCommand;
use App\Command\RecommendWorkoutCommand;
use App\Command\RemoveExerciseFromWorkoutCommand;
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
 * Class RecommendWorkoutCommandHandler
 * @package App\Handler\Command
 */
class RecommendWorkoutCommandHandler implements MessageHandlerInterface
{
    /**
     * @var WorkoutService
     */
    private $workoutService;

    /**
     * @var UserQueryService
     */
    private $userQueryService;

    /**
     * @var WorkoutQueryService
     */
    private $workoutQueryService;

    /**
     * RecommendWorkoutCommandHandler constructor.
     * @param WorkoutService $workoutService
     * @param UserQueryService $userQueryService
     * @param WorkoutQueryService $workoutQueryService
     */
    public function __construct(
        WorkoutService $workoutService,
        UserQueryService $userQueryService,
        WorkoutQueryService $workoutQueryService
    ) {
        $this->workoutService = $workoutService;
        $this->userQueryService = $userQueryService;
        $this->workoutQueryService = $workoutQueryService;
    }

    /**
     * @param RecommendWorkoutCommand $command
     * @throws EntityNotFoundException
     */
    public function __invoke(RecommendWorkoutCommand $command): void
    {
        $user = $this->userQueryService->findUserById($command->getUserId());
        if (!$user instanceof User) {
            throw new EntityNotFoundException('Entity #' . $command->getUserId() . ' not found');
        }

        $workout = $this->workoutQueryService->findWorkoutById($command->getWorkoutId());
        if (!$workout instanceof Workout) {
            throw new EntityNotFoundException('Entity #' . $command->getWorkoutId() . ' not found');
        }

        $this->workoutService->recommendWorkout(
            $user,
            $workout
        );
    }
}
