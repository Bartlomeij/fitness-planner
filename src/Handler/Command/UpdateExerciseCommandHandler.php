<?php

namespace App\Handler\Command;

use App\Command\UpdateExerciseCommand;
use App\Service\ExerciseService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class UpdateExerciseCommandHandler
 * @package App\Handler\Command
 */
class UpdateExerciseCommandHandler implements MessageHandlerInterface
{
    /**
     * @var ExerciseService
     */
    private $exerciseService;

    /**
     * UpdateExerciseCommandHandler constructor.
     * @param ExerciseService $exerciseService
     */
    public function __construct(ExerciseService $exerciseService)
    {
        $this->exerciseService = $exerciseService;
    }

    /**
     * @param UpdateExerciseCommand $command
     * @throws EntityNotFoundException
     */
    public function __invoke(UpdateExerciseCommand $command): void
    {
        $this->exerciseService->updateExercise(
            $command->getExerciseId(),
            $command->getTitle(),
            $command->getDifficultyLevel(),
            $command->getMinutes(),
        );
    }
}
