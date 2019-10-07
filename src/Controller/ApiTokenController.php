<?php

namespace App\Controller;

use App\Command\CreateUserCommand;
use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Form\Input\ApiTokenInput;
use App\Form\Input\UserInput;
use App\Form\Type\ApiTokenType;
use App\Form\Type\UserType;
use App\Service\ApiTokenService;
use App\Service\UserQueryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class ApiTokenController
 * @package App\Controller
 */
class ApiTokenController extends BaseController
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserQueryService $userQueryService
     * @param ApiTokenService $apiTokenService
     * @return JsonResponse
     *
     * @Route("/api/token", name="api_token_create", methods={"POST"})
     */
    public function createApiToken(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserQueryService $userQueryService,
        ApiTokenService $apiTokenService
    ): JsonResponse {
        $apiTokenInput = new ApiTokenInput();
        $form = $this->createForm(ApiTokenType::class, $apiTokenInput);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            return $this->createInvalidCredentialsJsonResponse();
        }

        $user = $userQueryService->findUserByEmail($apiTokenInput->getEmail());
        if (!$user instanceof User) {
            return $this->createInvalidCredentialsJsonResponse();
        }

        if (!$passwordEncoder->isPasswordValid($user, $apiTokenInput->getPassword())) {
            return $this->createInvalidCredentialsJsonResponse();
        }

        return $this->json([
            'token' => $apiTokenService->createApiToken($user)->getToken(),
        ], 201);
    }

    /**
     * @return JsonResponse
     */
    private function createInvalidCredentialsJsonResponse(): JsonResponse
    {
        return $this->json(
            ['message' => 'Invalid Credentials.'],
            401
        );
    }
}
