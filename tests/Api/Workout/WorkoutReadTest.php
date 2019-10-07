<?php

namespace App\Tests\Api\Workout;

use App\Entity\Workout;
use App\Tests\Api\ApiTestCase;

/**
 * Class WorkoutReadTest
 * @package App\Tests\Api\Workout
 */
class WorkoutReadTest extends ApiTestCase
{
    public function testCanShowWorkoutWithId(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $workout = $this->createWorkout('Super Test Workout Title');

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/workout/' . $workout->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($workout->getTitle(), $responseData['workout']['title']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCannotShowWorkoutIfNotExists(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $workout = $this->createWorkout();
        $workoutId = $workout->getId();
        $this->deleteWorkout($workout);

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/workout/' . $workoutId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Entity with id #' . $workoutId . ' does not exist.', $responseData['message']);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCanListEmptyWorkoutList(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/workout',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(0, count($responseData['workouts']));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCanListWorkouts(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $workouts = [];
        for ($i = 0; $i < 5; $i++) {
            $workouts[] = $this->createWorkout('ListTest #' . $i);
        }

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/workout',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(5, count($responseData['workouts']));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $i = 0;
        foreach ($workouts as $workout) {
            $responseWorkoutData = $responseData['workouts'][$i];

            /** @var Workout $workout */
            $this->assertEquals($workout->getId(), $responseWorkoutData['id']);
            $this->assertEquals($workout->getTitle(), $responseWorkoutData['title']);
            $i++;
        }
    }

    public function testCannotShowWorkoutIfNotLoggedIn(): void
    {
        $workout = $this->createWorkout();

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/workout/' . $workout->getId(),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testCannotListWorkoutsIfNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/workout',
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
