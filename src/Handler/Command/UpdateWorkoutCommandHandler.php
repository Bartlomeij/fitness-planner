<?php

namespace App\Handler\Command;

use App\Command\UpdateWorkoutCommand;
use App\Service\WorkoutService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class UpdateWorkoutCommandHandler
 * @package App\Handler\Command
 */
class UpdateWorkoutCommandHandler implements MessageHandlerInterface
{
    /**
     * @var WorkoutService
     */
    private $workoutService;

    /**
     * UpdateWorkoutCommandHandler constructor.
     * @param WorkoutService $workoutService
     */
    public function __construct(WorkoutService $workoutService)
    {
        $this->workoutService = $workoutService;
    }

    /**
     * @param UpdateWorkoutCommand $command
     * @throws EntityNotFoundException
     */
    public function __invoke(UpdateWorkoutCommand $command): void
    {
        $this->workoutService->updateWorkout(
            $command->getWorkoutId(),
            $command->getTitle(),
        );
    }
}
