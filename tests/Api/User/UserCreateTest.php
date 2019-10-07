<?php

namespace App\Tests\Api\User;

use App\Entity\User;
use App\Tests\Api\ApiTestCase;

/**
 * Class UserCreateTest
 * @package App\Tests\Api\User
 */
class UserCreateTest extends ApiTestCase
{
    public function testCanCreateUserWithEmailAndPassword(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/user', [], [], [], json_encode([
            'email' => 'testuser@example.com',
            'password' => 'verySafePassword',
        ]));
        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy([
            'email' => 'testuser@example.com'
        ]);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testCannotCreateUserWithoutEmail(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/user', [], [], [], json_encode([
            'password' => 'verySafePassword',
        ]));

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should not be blank.", $responseData['errors']['data.email']);
        $this->assertArrayNotHasKey('data.password', $responseData['errors']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateUserWithoutPassword(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/user', [], [], [], json_encode([
            'email' => 'testuser@example.com',
        ]));

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should not be blank.", $responseData['errors']['data.password']);
        $this->assertArrayNotHasKey('data.email', $responseData['errors']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateUserWithEmptyPassword(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/user', [], [], [], json_encode([
            'email' => 'testuser@example.com',
            'password' => '',
        ]));

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should not be blank.", $responseData['errors']['data.password']);
        $this->assertArrayNotHasKey('data.email', $responseData['errors']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateUserWithInvalidEmail(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/user', [], [], [], json_encode([
            'email' => 'testuser',
            'password' => 'verySafePassword',
        ]));

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value is not a valid email address.", $responseData['errors']['data.email']);
        $this->assertArrayNotHasKey('data.password', $responseData['errors']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateUserWithTooShortPassword(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/user', [], [], [], json_encode([
            'email' => 'testuser@example.com',
            'password' => 'secret',
        ]));

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            "This value is too short. It should have 8 characters or more.",
            $responseData['errors']['data.password']
        );
        $this->assertArrayNotHasKey('data.email', $responseData['errors']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateUserWithTooLongPassword(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/user', [], [], [], json_encode([
            'email' => 'testuser@example.com',
            'password' => 'secretVerySafePasswordButUnfortunatelyTooLong',
        ]));

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            "This value is too long. It should have 30 characters or less.",
            $responseData['errors']['data.password']
        );
        $this->assertArrayNotHasKey('data.email', $responseData['errors']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotCreateUserWithAlreadyExistingEmail(): void
    {
        $testEmail = 'testuser@example.com';
        $this->createUser($testEmail);

        $client = static::createClient();
        $client->request('POST', '/api/user', [], [], [], json_encode([
            'email' => $testEmail,
            'password' => 'verySafePassword',
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            'User with email ' . $testEmail . ' already exists!',
            json_decode($client->getResponse()->getContent())
        );
    }
}
