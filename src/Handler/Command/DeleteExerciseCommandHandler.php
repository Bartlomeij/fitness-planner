<?php

namespace App\Handler\Command;

use App\Command\DeleteExerciseCommand;
use App\Exception\ExerciseDeleteException;
use App\Service\ExerciseService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class DeleteExerciseCommandHandler
 * @package App\Handler\Command
 */
class DeleteExerciseCommandHandler implements MessageHandlerInterface
{
    /**
     * @var ExerciseService
     */
    private $exerciseService;

    /**
     * DeleteExerciseCommandHandler constructor.
     * @param ExerciseService $exerciseService
     */
    public function __construct(ExerciseService $exerciseService)
    {
        $this->exerciseService = $exerciseService;
    }

    /**
     * @param DeleteExerciseCommand $command
     * @throws EntityNotFoundException
     * @throws ExerciseDeleteException
     */
    public function __invoke(DeleteExerciseCommand $command): void
    {
        $this->exerciseService->deleteExercise(
            $command->getExerciseId(),
        );
    }
}
