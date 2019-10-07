<?php

namespace App\Tests\Api\Workout;

use App\Entity\Workout;
use App\Tests\Api\ApiTestCase;

/**
 * Class ExerciseReadTest
 * @package App\Tests\Api\Workout
 */
class ExerciseReadTest extends ApiTestCase
{
    public function testCanShowExerciseWithId(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $exercise = $this->createExercise('Super Test Exercise Title');

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/exercise/' . $exercise->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($exercise->getTitle(), $responseData['exercise']['title']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCannotShowExerciseIfNotExists(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $exercise = $this->createExercise();
        $exerciseId = $exercise->getId();
        $this->deleteExercise($exercise);

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/exercise/' . $exerciseId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Entity with id #' . $exerciseId . ' does not exist.', $responseData['message']);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCanListEmptyWorkoutList(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/exercise',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(0, count($responseData['exercises']));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCanListWorkouts(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $exercises = [];
        for ($i = 0; $i < 5; $i++) {
            $exercises[] = $this->createExercise('ListTestExercise #' . $i);
        }

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/exercise',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(5, count($responseData['exercises']));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $i = 0;
        foreach ($exercises as $exercise) {
            $responseExerciseData = $responseData['exercises'][$i];

            /** @var Workout $workout */
            $this->assertEquals($exercise->getId(), $responseExerciseData['id']);
            $this->assertEquals($exercise->getTitle(), $responseExerciseData['title']);
            $i++;
        }
    }

    public function testCannotShowWorkoutIfNotLoggedIn(): void
    {
        $exercise = $this->createExercise();

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/exercise/' . $exercise->getId(),
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
            '/api/exercise',
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
