<?php

namespace App\Command;

/**
 * Class CreateWorkoutCommand
 * @package App\Command
 */
class CreateWorkoutCommand
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $userId;

    /**
     * CreateWorkoutCommand constructor.
     * @param string $title
     * @param int $userId
     */
    public function __construct(string $title, int $userId)
    {
        $this->title = $title;
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
