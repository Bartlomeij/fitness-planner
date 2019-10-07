<?php

namespace App\Command;

/**
 * Class DeleteExerciseCommand
 * @package App\Command
 */
class DeleteExerciseCommand
{
    /**
     * @var int
     */
    private $exerciseId;

    /**
     * DeleteExerciseCommand constructor.
     * @param int $exerciseId
     */
    public function __construct(int $exerciseId)
    {
        $this->exerciseId = $exerciseId;
    }

    /**
     * @return int
     */
    public function getExerciseId(): int
    {
        return $this->exerciseId;
    }
}
