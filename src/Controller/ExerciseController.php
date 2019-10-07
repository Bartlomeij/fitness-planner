<?php

namespace App\Controller;

use App\Command\CreateExerciseCommand;
use App\Command\DeleteExerciseCommand;
use App\Command\UpdateExerciseCommand;
use App\Entity\Exercise;
use App\Exception\ExerciseDeleteException;
use App\Form\Input\ExerciseInput;
use App\Form\Type\ExerciseType;
use App\Service\ExerciseQueryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ExerciseController
 * @package App\Controller
 */
class ExerciseController extends BaseController
{
    /**
     * @param Request $request
     * @param MessageBusInterface $messageBus
     * @return JsonResponse
     *
     * @Route("/api/exercise", name="api_exercise_create", methods={"POST"})
     */
    public function createExercise(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $exerciseInput = new ExerciseInput();
        $form = $this->createForm(ExerciseType::class, $exerciseInput);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            return $this->createFormErrorJsonResponse($form);
        }

        try {
            $messageBus->dispatch(new CreateExerciseCommand(
                $exerciseInput->getTitle(),
                $exerciseInput->getDifficultyLevel(),
                $exerciseInput->getMinutes(),
                $this->getUser()->getId()
            ));
        } catch (HandlerFailedException $exception) {
            return $this->createInternalErrorJsonResponse($exception);
        }

        return $this->json(null, 201);
    }

    /**
     * @param string $exerciseId
     * @param MessageBusInterface $messageBus
     * @param ExerciseQueryService $exerciseQueryService
     * @return JsonResponse
     *
     * @Route("/api/exercise/{exerciseId}", name="api_exercise_delete", methods={"DELETE"})
     */
    public function deleteExercise(
        string $exerciseId,
        MessageBusInterface $messageBus,
        ExerciseQueryService $exerciseQueryService
    ): JsonResponse {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $exercise = $exerciseQueryService->findExerciseById((int)$exerciseId);
        if (!$exercise instanceof Exercise) {
            return $this->createEntityNotFoundJsonResponse($exerciseId);
        }

        if ($exercise->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->createForbiddenAccessJsonResponse();
        }

        try {
            $messageBus->dispatch(new DeleteExerciseCommand(
                $exercise->getId(),
            ));
        } catch (HandlerFailedException $exception) {
            if ($exception->getPrevious() instanceof ExerciseDeleteException) {
                return $this->json($exception->getMessage(), 400);
            }
            return $this->createInternalErrorJsonResponse($exception);
        }

        return $this->json(null, 204);
    }

    /**
     * @param string $exerciseId
     * @param Request $request
     * @param MessageBusInterface $messageBus
     * @param ExerciseQueryService $exerciseQueryService
     * @return JsonResponse
     *
     * @Route("/api/exercise/{exerciseId}", name="api_exercise_update", methods={"PUT"})
     */
    public function updateExercise(
        string $exerciseId,
        Request $request,
        MessageBusInterface $messageBus,
        ExerciseQueryService $exerciseQueryService
    ): JsonResponse {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $exerciseInput = new ExerciseInput();
        $form = $this->createForm(ExerciseType::class, $exerciseInput);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            return $this->createFormErrorJsonResponse($form);
        }

        $exercise = $exerciseQueryService->findExerciseById((int)$exerciseId);
        if (!$exercise instanceof Exercise) {
            return $this->createEntityNotFoundJsonResponse($exerciseId);
        }

        if ($exercise->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->createForbiddenAccessJsonResponse();
        }

        try {
            $messageBus->dispatch(new UpdateExerciseCommand(
                $exercise->getId(),
                $exerciseInput->getTitle(),
                $exerciseInput->getDifficultyLevel(),
                $exerciseInput->getMinutes(),
            ));
        } catch (HandlerFailedException $exception) {
            return $this->createInternalErrorJsonResponse($exception);
        }

        return $this->json(null, 204);
    }

    /**
     * @param string $exerciseId
     * @param ExerciseQueryService $exerciseQueryService
     * @return JsonResponse
     *
     * @Route("/api/exercise/{exerciseId}", name="api_exercise_show", methods={"GET"})
     */
    public function showExercise(string $exerciseId, ExerciseQueryService $exerciseQueryService): JsonResponse
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $exercise = $exerciseQueryService->findExerciseById((int)$exerciseId);
        if (!$exercise instanceof Exercise) {
            return $this->createEntityNotFoundJsonResponse($exerciseId);
        }

        return $this->json([
            'exercise' => $exercise->toArray()
        ], 200);
    }

    /**
     * @param ExerciseQueryService $exerciseQueryService
     * @return JsonResponse
     *
     * @Route("/api/exercise", name="api_exercise_list", methods={"GET"})
     */
    public function listExercise(ExerciseQueryService $exerciseQueryService): JsonResponse
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        return $this->json([
            'exercises' => $exerciseQueryService->getAllExercisesArray()
        ], 200);
    }
}
