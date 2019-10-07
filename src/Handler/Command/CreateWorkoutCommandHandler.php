<?php

namespace App\Handler\Command;

use App\Command\CreateWorkoutCommand;
use App\Entity\User;
use App\Service\UserQueryService;
use App\Service\WorkoutService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class CreateWorkoutCommandHandler
 * @package App\Handler\Command
 */
class CreateWorkoutCommandHandler implements MessageHandlerInterface
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
     * CreateWorkoutCommandHandler constructor.
     * @param WorkoutService $workoutService
     * @param UserQueryService $userQueryService
     */
    public function __construct(WorkoutService $workoutService, UserQueryService $userQueryService)
    {
        $this->workoutService = $workoutService;
        $this->userQueryService = $userQueryService;
    }

    /**
     * @param CreateWorkoutCommand $command
     * @throws EntityNotFoundException
     */
    public function __invoke(CreateWorkoutCommand $command): void
    {
        $user = $this->userQueryService->findUserById($command->getUserId());
        if (!$user instanceof User) {
            throw new EntityNotFoundException('Entity #' . $command->getUserId() . ' not found');
        }

        $this->workoutService->createWorkout(
            $command->getTitle(),
            $user,
        );
    }
}
