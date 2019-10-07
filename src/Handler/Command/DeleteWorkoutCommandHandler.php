<?php

namespace App\Handler\Command;

use App\Command\DeleteWorkoutCommand;
use App\Service\WorkoutService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class DeleteWorkoutCommandHandler
 * @package App\Handler\Command
 */
class DeleteWorkoutCommandHandler implements MessageHandlerInterface
{
    /**
     * @var WorkoutService
     */
    private $workoutService;

    /**
     * DeleteWorkoutCommandHandler constructor.
     * @param WorkoutService $workoutService
     */
    public function __construct(WorkoutService $workoutService)
    {
        $this->workoutService = $workoutService;
    }

    /**
     * @param DeleteWorkoutCommand $command
     * @throws EntityNotFoundException
     */
    public function __invoke(DeleteWorkoutCommand $command): void
    {
        $this->workoutService->deleteWorkout(
            $command->getId(),
        );
    }
}
