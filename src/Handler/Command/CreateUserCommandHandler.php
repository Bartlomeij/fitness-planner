<?php

namespace App\Handler\Command;

use App\Command\CreateUserCommand;
use App\Exception\UserAlreadyExistsException;
use App\Service\UserService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateUserCommandHandler implements MessageHandlerInterface
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * CreateUserCommandHandler constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param CreateUserCommand $command
     * @throws UserAlreadyExistsException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $this->userService->createUser(
            $command->getEmail(),
            $command->getPassword(),
        );
    }
}
