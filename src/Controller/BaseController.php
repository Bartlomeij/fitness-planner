<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseController
 * @package App\Controller
 */
class BaseController extends AbstractController
{
    /**
     * @param Request $request
     * @param FormInterface $form
     */
    protected function processForm(Request $request, FormInterface $form): void
    {
        $data = json_decode($request->getContent(), true);

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    /**
     * @param FormInterface $form
     * @return JsonResponse
     */
    protected function createFormErrorJsonResponse(FormInterface $form): JsonResponse
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $propertyPath = $error->getCause() ? $error->getCause()->getPropertyPath() : null;
            $errors[$propertyPath] = $error->getMessage();
        }

        return $this->json([
            'errors' => $errors
        ], 400);
    }

    /**
     * @param Exception $exception
     * @return JsonResponse
     */
    protected function createInternalErrorJsonResponse(Exception $exception): JsonResponse
    {
        return $this->json([
            'message' => $exception->getMessage()
        ], 500);
    }

    /**
     * @param string $entityId
     * @return JsonResponse
     */
    protected function createEntityNotFoundJsonResponse(string $entityId): JsonResponse
    {
        return $this->json(
            ['message' => 'Entity with id #' . $entityId . ' does not exist.'],
            404
        );
    }

    /**
     * @return JsonResponse
     */
    protected function createForbiddenAccessJsonResponse(): JsonResponse
    {
        return $this->json(
            ['message' => 'Forbidden Access'],
            403
        );
    }
}
