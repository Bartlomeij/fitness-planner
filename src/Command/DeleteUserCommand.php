<?php

namespace App\Command;

/**
 * Class DeleteUserCommand
 * @package App\Command
 */
class DeleteUserCommand
{
    /**
     * @var int
     */
    private $id;

    /**
     * DeleteUserCommand constructor.
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
