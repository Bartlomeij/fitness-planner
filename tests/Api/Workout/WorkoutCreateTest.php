<?php

namespace App\Tests\Api\Workout;

use App\Entity\Workout;
use App\Tests\Api\ApiTestCase;

/**
 * Class WorkoutCreateTest
 * @package App\Tests\Api\Workout
 */
class WorkoutCreateTest extends ApiTestCase
{
    public function testCanCreateWorkoutWithTitle(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/workout',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'title' => 'testWorkout',
            ]),
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $workout = $this->getEntityManager()->getRepository(Workout::class)->findOneBy([
            'title' => 'testWorkout'
        ]);
        $this->assertInstanceOf(Workout::class, $workout);
        $this->assertEquals($user->getId(), $workout->getUser()->getId());
        $this->assertEquals('{}', $client->getResponse()->getContent());
    }

    public function testCannotCreateWorkoutWithoutTitle(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/workout',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should not be blank.", $responseData['errors']['data.title']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateWorkoutIfNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/workout',
            [],
            [],
            [],
            json_encode([
                'title' => 'testWorkout',
            ]),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
