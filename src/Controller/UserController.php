<?php

namespace App\Controller;

use App\Command\CreateUserCommand;
use App\Command\DeleteUserCommand;
use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Form\Input\UserInput;
use App\Form\Type\UserType;
use App\Service\UserQueryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends BaseController
{
    /**
     * @param Request $request
     * @param MessageBusInterface $messageBus
     * @return JsonResponse
     *
     * @Route("/api/user", name="api_user_create", methods={"POST"})
     */
    public function createUser(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $userInput = new UserInput();
        $form = $this->createForm(UserType::class, $userInput);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            return $this->createFormErrorJsonResponse($form);
        }

        try {
            $messageBus->dispatch(new CreateUserCommand(
                $userInput->getEmail(),
                $userInput->getPassword(),
            ));
        } catch (HandlerFailedException $exception) {
            if ($exception->getPrevious() instanceof UserAlreadyExistsException) {
                return $this->json($exception->getMessage(), 400);
            }
            return $this->createInternalErrorJsonResponse($exception);
        }

        return $this->json(null, 201);
    }

    /**
     * @param string $userId
     * @param MessageBusInterface $messageBus
     * @param UserQueryService $userQueryService
     * @return JsonResponse
     *
     * @Route("/api/user/{userId}", name="api_user_delete", methods={"DELETE"})
     */
    public function deleteUser(
        string $userId,
        MessageBusInterface $messageBus,
        UserQueryService $userQueryService
    ): JsonResponse {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $user = $userQueryService->findUserById((int)$userId);
        if (!$user instanceof User) {
            return $this->createEntityNotFoundJsonResponse($userId);
        }

        if ($user->getId() !== $this->getUser()->getId()) {
            return $this->createForbiddenAccessJsonResponse();
        }

        try {
            $messageBus->dispatch(new DeleteUserCommand(
                $user->getId(),
            ));
        } catch (HandlerFailedException $exception) {
            return $this->createInternalErrorJsonResponse($exception);
        }

        return $this->json(null, 204);
    }
}
