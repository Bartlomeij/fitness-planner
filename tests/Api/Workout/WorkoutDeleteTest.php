<?php

namespace App\Tests\Api\Workout;

use App\Entity\Workout;
use App\Tests\Api\ApiTestCase;

/**
 * Class WorkoutUpdateTest
 * @package App\Tests\Api\Workout
 */
class WorkoutDeleteTest extends ApiTestCase
{
    public function testCanDeleteWorkoutWithId(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $workout = $this->createWorkout('Test Workout', $user);

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/workout/' . $workout->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );
        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $workout = $this->getEntityManager()->getRepository(Workout::class)->find($workout->getId());
        $this->assertNull($workout);
    }

    public function testCannotDeleteWorkoutIfNotExists(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $workout = $this->createWorkout();
        $workoutId = $workout->getId();
        $this->deleteWorkout($workout);

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/workout/' . $workoutId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Entity with id #' . $workoutId . ' does not exist.', $responseData['message']);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCannotDeleteWorkoutIfNotLoggedIn(): void
    {
        $workout = $this->createWorkout();

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/workout/' . $workout->getId(),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testCannotDeleteWorkoutIfNotOwner(): void
    {
        $workout = $this->createWorkout();

        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/workout/' . $workout->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
