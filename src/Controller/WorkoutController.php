<?php

namespace App\Controller;

use App\Command\AddExerciseToWorkoutCommand;
use App\Command\CreateWorkoutCommand;
use App\Command\DeleteWorkoutCommand;
use App\Command\RecommendWorkoutCommand;
use App\Command\RemoveExerciseFromWorkoutCommand;
use App\Command\UpdateWorkoutCommand;
use App\Entity\Exercise;
use App\Entity\Recommendation;
use App\Entity\Workout;
use App\Form\Input\WorkoutInput;
use App\Form\Type\WorkoutType;
use App\Service\ExerciseQueryService;
use App\Service\RecommendationQueryService;
use App\Service\WorkoutQueryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WorkoutController
 * @package App\Controller
 */
class WorkoutController extends BaseController
{
    /**
     * @param Request $request
     * @param MessageBusInterface $messageBus
     * @return JsonResponse
     *
     * @Route("/api/workout", name="api_workout_create", methods={"POST"})
     */
    public function createWorkout(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $workoutInput = new WorkoutInput();
        $form = $this->createForm(WorkoutType::class, $workoutInput);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            return $this->createFormErrorJsonResponse($form);
        }

        try {
            $messageBus->dispatch(new CreateWorkoutCommand(
                $workoutInput->getTitle(),
                $this->getUser()->getId()
            ));
        } catch (HandlerFailedException $exception) {
            return $this->createInternalErrorJsonResponse($exception);
        }

        return $this->json(null, 201);
    }

    /**
     * @param string $workoutId
     * @param MessageBusInterface $messageBus
     * @param WorkoutQueryService $workoutQueryService
     * @return JsonResponse
     *
     * @Route("/api/workout/{workoutId}", name="api_workout_delete", methods={"DELETE"})
     */
    public function deleteWorkout(
        string $workoutId,
        MessageBusInterface $messageBus,
        WorkoutQueryService $workoutQueryService
    ): JsonResponse {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $workout = $workoutQueryService->findWorkoutById((int)$workoutId);
        if (!$workout instanceof Workout) {
            return $this->createEntityNotFoundJsonResponse($workoutId);
        }

        if ($workout->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->createForbiddenAccessJsonResponse();
        }

        try {
            $messageBus->dispatch(new DeleteWorkoutCommand(
                $workout->getId(),
            ));
        } catch (HandlerFailedException $exception) {
            return $this->createInternalErrorJsonResponse($exception);
        }

        return $this->json(null, 204);
    }

    /**
     * @param string $workoutId
     * @param Request $request
     * @param MessageBusInterface $messageBus
     * @param WorkoutQueryService $workoutQueryService
     * @return JsonResponse
     *
     * @Route("/api/workout/{workoutId}", name="api_workout_update", methods={"PUT"})
     */
    public function updateWorkout(
        string $workoutId,
        Request $request,
        MessageBusInterface $messageBus,
        WorkoutQueryService $workoutQueryService
    ): JsonResponse {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $workoutInput = new WorkoutInput();
        $form = $this->createForm(WorkoutType::class, $workoutInput);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            return $this->createFormErrorJsonResponse($form);
        }

        $workout = $workoutQueryService->findWorkoutById((int)$workoutId);
        if (!$workout instanceof Workout) {
            return $this->createEntityNotFoundJsonResponse($workoutId);
        }

        if ($workout->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->createForbiddenAccessJsonResponse();
        }

        try {
            $messageBus->dispatch(new UpdateWorkoutCommand(
                $workout->getId(),
                $workoutInput->getTitle(),
            ));
        } catch (HandlerFailedException $exception) {
            return $this->createInternalErrorJsonResponse($exception);
        }

        return $this->json(null, 204);
    }

    /**
     * @param string $workoutId
     * @param WorkoutQueryService $workoutQueryService
     * @return JsonResponse
     *
     * @Route("/api/workout/{workoutId}", name="api_workout_show", methods={"GET"})
     */
    public function showWorkout(string $workoutId, WorkoutQueryService $workoutQueryService): JsonResponse
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $workout = $workoutQueryService->findWorkoutById((int)$workoutId);
        if (!$workout instanceof Workout) {
            return $this->createEntityNotFoundJsonResponse($workoutId);
        }

        return $this->json([
            'workout' => $workout->toArray()
        ], 200);
    }

    /**
     * @param WorkoutQueryService $workoutQueryService
     * @return JsonResponse
     *
     * @Route("/api/workout", name="api_workout_list", methods={"GET"})
     */
    public function listWorkout(WorkoutQueryService $workoutQueryService): JsonResponse
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        return $this->json([
            'workouts' => $workoutQueryService->getAllWorkoutsArray()
        ], 200);
    }

    /**
     * @param string $workoutId
     * @param string $exerciseId
     * @param MessageBusInterface $messageBus
     * @param WorkoutQueryService $workoutQueryService
     * @param ExerciseQueryService $exerciseQueryService
     * @return JsonResponse
     *
     * @Route("/api/workout/{workoutId}/exercise/{exerciseId}", name="api_workout_add_exercise", methods={"POST"})
     */
    public function addExerciseToWorkout(
        string $workoutId,
        string $exerciseId,
        MessageBusInterface $messageBus,
        WorkoutQueryService $workoutQueryService,
        ExerciseQueryService $exerciseQueryService
    ): JsonResponse {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $workout = $workoutQueryService->findWorkoutById((int)$workoutId);
        if (!$workout instanceof Workout) {
            return $this->createEntityNotFoundJsonResponse($workoutId);
        }

        if ($workout->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $exercise = $exerciseQueryService->findExerciseById((int)$exerciseId);
        if (!$exercise instanceof Exercise) {
            return $this->createEntityNotFoundJsonResponse($exerciseId);
        }

        if ($workout->getMinutes() + $exercise->getMinutes() > Workout::WORKOUT_TIME_LIMIT_IN_MINUTES) {
            return $this->createForbiddenAccessJsonResponse();
        }

        try {
            $messageBus->dispatch(new AddExerciseToWorkoutCommand(
                $workout->getId(),
                $exercise->getId(),
            ));
        } catch (HandlerFailedException $exception) {
            return $this->createInternalErrorJsonResponse($exception);
        }

        return $this->json(null, 201);
    }

    /**
     * @param string $workoutId
     * @param string $exerciseId
     * @param MessageBusInterface $messageBus
     * @param WorkoutQueryService $workoutQueryService
     * @param ExerciseQueryService $exerciseQueryService
     * @return JsonResponse
     *
     * @Route("/api/workout/{workoutId}/exercise/{exerciseId}", name="api_workout_remove_exercise", methods={"DELETE"})
     */
    public function removeExerciseFromWorkout(
        string $workoutId,
        string $exerciseId,
        MessageBusInterface $messageBus,
        WorkoutQueryService $workoutQueryService,
        ExerciseQueryService $exerciseQueryService
    ): JsonResponse {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $workout = $workoutQueryService->findWorkoutById((int)$workoutId);
        if (!$workout instanceof Workout) {
            return $this->createEntityNotFoundJsonResponse($workoutId);
        }

        if ($workout->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $exercise = $exerciseQueryService->findExerciseById((int)$exerciseId);
        if (!$exercise instanceof Exercise) {
            return $this->createEntityNotFoundJsonResponse($exerciseId);
        }

        try {
            $messageBus->dispatch(new RemoveExerciseFromWorkoutCommand(
                $workout->getId(),
                $exercise->getId(),
            ));
        } catch (HandlerFailedException $exception) {
            return $this->createInternalErrorJsonResponse($exception);
        }

        return $this->json(null, 204);
    }

    /**
     * @param string $workoutId
     * @param MessageBusInterface $messageBus
     * @param WorkoutQueryService $workoutQueryService
     * @param RecommendationQueryService $recommendationQueryService
     * @return JsonResponse
     * @throws \Exception
     *
     * @Route("/api/workout/{workoutId}/recommend", name="api_workout_recommend", methods={"POST"})
     */
    public function recommendWorkout(
        string $workoutId,
        MessageBusInterface $messageBus,
        WorkoutQueryService $workoutQueryService,
        RecommendationQueryService $recommendationQueryService
    ): JsonResponse {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createForbiddenAccessJsonResponse();
        }

        $workout = $workoutQueryService->findWorkoutById((int)$workoutId);
        if (!$workout instanceof Workout) {
            return $this->createEntityNotFoundJsonResponse($workoutId);
        }

        if ($workout->getUser()->getId() === $this->getUser()->getId()) {
            return $this->createForbiddenAccessJsonResponse();
        }

        if (
            $recommendationQueryService->countByUserIdAndDate(
                $this->getUser(),
                new \DateTime()
            ) >= Recommendation::RECOMMENDATION_LIMIT_PER_DAY
        ) {
            return $this->createForbiddenAccessJsonResponse();
        }

        if ($recommendationQueryService->findByUserAndWorkout($this->getUser(), $workout)) {
            return $this->createForbiddenAccessJsonResponse();
        }

        try {
            $messageBus->dispatch(new RecommendWorkoutCommand(
                $workout->getId(),
                $this->getUser()->getId(),
            ));
        } catch (HandlerFailedException $exception) {
            return $this->createInternalErrorJsonResponse($exception);
        }

        return $this->json(null, 201);
    }
}
