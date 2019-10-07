<?php

namespace App\Handler\Command;

use App\Command\DeleteUserCommand;
use App\Service\UserService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class DeleteUserCommandHandler
 * @package App\Handler\Command
 */
class DeleteUserCommandHandler implements MessageHandlerInterface
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * DeleteUserCommandHandler constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param DeleteUserCommand $command
     * @throws EntityNotFoundException
     */
    public function __invoke(DeleteUserCommand $command): void
    {
        $this->userService->deleteUser(
            $command->getId(),
        );
    }
}
