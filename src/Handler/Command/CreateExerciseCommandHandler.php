<?php

namespace App\Handler\Command;

use App\Command\CreateExerciseCommand;
use App\Entity\User;
use App\Service\ExerciseService;
use App\Service\UserQueryService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class CreateExerciseCommandHandler
 * @package App\Handler\Command
 */
class CreateExerciseCommandHandler implements MessageHandlerInterface
{
    /**
     * @var ExerciseService
     */
    private $exerciseService;

    /**
     * @var UserQueryService
     */
    private $userQueryService;

    /**
     * CreateExerciseCommandHandler constructor.
     * @param ExerciseService $exerciseService
     * @param UserQueryService $userQueryService
     */
    public function __construct(ExerciseService $exerciseService, UserQueryService $userQueryService)
    {
        $this->exerciseService = $exerciseService;
        $this->userQueryService = $userQueryService;
    }

    /**
     * @param CreateExerciseCommand $command
     * @throws EntityNotFoundException
     */
    public function __invoke(CreateExerciseCommand $command): void
    {
        $user = $this->userQueryService->findUserById($command->getUserId());
        if (!$user instanceof User) {
            throw new EntityNotFoundException('Entity #' . $command->getUserId() . ' not found');
        }

        $this->exerciseService->createExercise(
            $command->getTitle(),
            $command->getDifficultyLevel(),
            $command->getMinutes(),
            $user,
        );
    }
}
