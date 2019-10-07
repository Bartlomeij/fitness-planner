<?php

namespace App\Tests\Api\Exercise;

use App\Entity\Exercise;
use App\Tests\Api\ApiTestCase;

/**
 * Class ExerciseDeleteTest
 * @package App\Tests\Api\Exercise
 */
class ExerciseDeleteTest extends ApiTestCase
{
    public function testCanDeleteExerciseWithId(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $exercise = $this->createExercise('Test Exercise', 5, 30, $user);

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/exercise/' . $exercise->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );
        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $exercise = $this->getEntityManager()->getRepository(Exercise::class)->find($exercise->getId());
        $this->assertNull($exercise);
    }

    public function testCannotDeleteExerciseIfNotExists(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $exercise = $this->createExercise();
        $exerciseId = $exercise->getId();
        $this->deleteExercise($exercise);

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/exercise/' . $exerciseId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Entity with id #' . $exerciseId . ' does not exist.', $responseData['message']);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCannotDeleteExerciseIfNotLoggedIn(): void
    {
        $exercise = $this->createExercise();

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/exercise/' . $exercise->getId(),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testCannotDeleteExerciseIfNotOwner(): void
    {
        $exercise = $this->createExercise();

        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/exercise/' . $exercise->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
