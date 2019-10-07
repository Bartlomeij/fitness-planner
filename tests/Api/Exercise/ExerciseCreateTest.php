<?php

namespace App\Tests\Api\Exercise;

use App\Entity\Exercise;
use App\Tests\Api\ApiTestCase;

/**
 * Class ExerciseCreateTest
 * @package App\Tests\Api\Exercise
 */
class ExerciseCreateTest extends ApiTestCase
{
    public function testCanCreateExerciseWithTitleDifficultyLevelAndMinutes(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/exercise',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'title' => 'testExercise',
                'difficultyLevel' => 3,
                'minutes' => 20
            ]),
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        /** @var Exercise $exercise */
        $exercise = $this->getEntityManager()->getRepository(Exercise::class)->findOneBy([
            'title' => 'testExercise',
        ]);

        $this->assertInstanceOf(Exercise::class, $exercise);
        $this->assertEquals($user->getId(), $exercise->getUser()->getId());
        $this->assertEquals(3, $exercise->getDifficultyLevel());
        $this->assertEquals(20, $exercise->getMinutes());
        $this->assertEquals('{}', $client->getResponse()->getContent());
    }

    public function testCannotCreateExerciseWithoutTitle(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/exercise',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'difficultyLevel' => 3,
                'minutes' => 20
            ]),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should not be blank.", $responseData['errors']['data.title']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateExerciseWithoutParams(): void
    {

        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/exercise',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should not be blank.", $responseData['errors']['data.title']);
        $this->assertEquals("This value should not be blank.", $responseData['errors']['data.difficultyLevel']);
        $this->assertEquals("This value should not be blank.", $responseData['errors']['data.minutes']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateExerciseWithoutDifficultyLevel(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/exercise',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'title' => 'testExercise',
                'minutes' => 20
            ]),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should not be blank.", $responseData['errors']['data.difficultyLevel']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateExerciseWithoutMinutes(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/exercise',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'title' => 'testExercise',
                'difficultyLevel' => 3
            ]),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should not be blank.", $responseData['errors']['data.minutes']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateExerciseWithDifficultyLevelOutOfRange1(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/exercise',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'title' => 'testExercise',
                'minutes' => 20,
                'difficultyLevel' => 0
            ]),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should be 1 or more.", $responseData['errors']['data.difficultyLevel']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateExerciseWithDifficultyLevelOutOfRange3(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/exercise',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'title' => 'testExercise',
                'minutes' => 20,
                'difficultyLevel' => 10
            ]),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should be 5 or less.", $responseData['errors']['data.difficultyLevel']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateExerciseWithMinutesOutOfRange1(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/exercise',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'title' => 'testExercise',
                'minutes' => 0,
                'difficultyLevel' => 3
            ]),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should be 1 or more.", $responseData['errors']['data.minutes']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateExerciseWithMinutesOutOfRange2(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/exercise',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'title' => 'testExercise',
                'minutes' => 100,
                'difficultyLevel' => 3
            ]),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should be 60 or less.", $responseData['errors']['data.minutes']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateWorkoutIfNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/exercise',
            [],
            [],
            [],
            json_encode([
                'difficultyLevel' => 3,
                'minutes' => 20
            ]),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
