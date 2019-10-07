<?php

namespace App\Tests\Api\Workout;

use App\Entity\User;
use App\Entity\Workout;
use App\Tests\Api\ApiTestCase;

/**
 * Class UserDeleteTest
 * @package App\Tests\Api\Workout
 */
class UserDeleteTest extends ApiTestCase
{
    public function testCanDeleteUserWithId(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/user/' . $user->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );
        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $user = $this->getEntityManager()->getRepository(User::class)->find($user->getId());
        $this->assertNull($user);
    }

    public function testCannotDeleteUserIfNotExists(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $removedUser = $this->createUser('testaccount+dev@example.com');
        $removedUserId = $removedUser->getId();
        $this->deleteUser($removedUser);

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/user/' . $removedUserId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Entity with id #' . $removedUserId . ' does not exist.', $responseData['message']);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCannotDeleteUserIfNotLoggedIn(): void
    {
        $user = $this->createUser('testaccount@example.com');

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/user/' . $user->getId(),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testCannotDeleteUserIfNotOwner(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $user2 = $this->createUser('testaccount+dev@example.com');

        $client = static::createClient();
        $client->request(
            'DELETE',
            '/api/user/' . $user2->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
