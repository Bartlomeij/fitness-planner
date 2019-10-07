<?php

namespace App\Tests\Api\Workout;

use App\Entity\Workout;
use App\Tests\Api\ApiTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class WorkoutUpdateTest
 * @package App\Tests\Api\Workout
 */
class WorkoutUpdateTest extends ApiTestCase
{
    public function testCanUpdateWorkoutWithIdAndTitle(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $workout = $this->createWorkout('Test Workout Title', $user);

        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/workout/' . $workout->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode(['title' => 'testWorkout123']),
        );
        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        /** @var Workout $workout */
        $workout = $this->getEntityManager()->getRepository(Workout::class)->find($workout->getId());
        $this->assertInstanceOf(Workout::class, $workout);
        $this->assertEquals('testWorkout123', $workout->getTitle());
    }

    public function testCannotUpdateWorkoutWithoutTitle(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $workout = $this->createWorkout('Test Workout Title');

        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/workout/' . $workout->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should not be blank.", $responseData['errors']['data.title']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotUpdateWorkoutWithTextId(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/workout/test',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode(['title' => 'testWorkout123']),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Entity with id #test does not exist.", $responseData['message']);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCannotUpdateWorkoutIfNotExists(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $workout = $this->createWorkout();
        $workoutId = $workout->getId();
        $this->deleteWorkout($workout);

        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/workout/' . $workoutId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode(['title' => 'testWorkout123']),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Entity with id #' . $workoutId . ' does not exist.', $responseData['message']);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCannotUpdateWorkoutIfNotOwner(): void
    {
        $workout = $this->createWorkout();

        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/workout/' . $workout->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode(['title' => 'testWorkout123']),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
