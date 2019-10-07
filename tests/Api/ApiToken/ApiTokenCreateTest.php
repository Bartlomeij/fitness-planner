<?php

namespace App\Tests\Api\ApiToken;

use App\Tests\Api\ApiTestCase;

/**
 * Class ApiTokenCreateTest
 * @package App\Tests\Api\ApiToken
 */
class ApiTokenCreateTest extends ApiTestCase
{
    public function testCanCreateApiTokenWithEmailAndPassword(): void
    {
        $email = 'test_email@example.com';
        $password = 'verySecretPass';
        $this->createUser($email, $password);

        $client = static::createClient();
        $client->request('POST', '/api/token', [], [], [], json_encode([
            'email' => $email,
            'password' => $password,
        ]));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals("application/json", $client->getResponse()->headers->get('Content-Type'));

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseData);
    }
}
