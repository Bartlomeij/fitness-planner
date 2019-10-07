<?php

namespace App\Command;

/**
 * Class DeleteWorkoutCommand
 * @package App\Command
 */
class DeleteWorkoutCommand
{
    /**
     * @var int
     */
    private $id;

    /**
     * DeleteWorkoutCommand constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
