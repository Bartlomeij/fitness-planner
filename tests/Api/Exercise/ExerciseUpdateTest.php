<?php

namespace App\Tests\Api\Workout;

use App\Entity\Exercise;
use App\Entity\Workout;
use App\Tests\Api\ApiTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ExerciseUpdateTest
 * @package App\Tests\Api\Workout
 */
class ExerciseUpdateTest extends ApiTestCase
{
    public function testCanUpdateExerciseWithIdTitleDifficultyLevelAndMinutes(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $exercise = $this->createExercise('Test Exercise Title', 3, 20, $user);

        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/exercise/' . $exercise->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'title' => 'testExercise123',
                'difficultyLevel' => 4,
                'minutes' => 30
            ]),
        );
        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        /** @var Exercise $exercise */
        $exercise = $this->getEntityManager()->getRepository(Exercise::class)->find($exercise->getId());
        $this->assertInstanceOf(Exercise::class, $exercise);
        $this->assertEquals('testExercise123', $exercise->getTitle());
        $this->assertEquals(4, $exercise->getDifficultyLevel());
        $this->assertEquals(30, $exercise->getMinutes());
    }

    public function testCannotUpdateExerciseWithoutTitle(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $exercise = $this->createExercise('Test Exercise Title', 3, 20, $user);

        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/exercise/' . $exercise->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'difficultyLevel' => 4,
                'minutes' => 30
            ]),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("This value should not be blank.", $responseData['errors']['data.title']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCannotUpdateWorkoutIfNotExists(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $exercise = $this->createExercise();
        $exerciseId = $exercise->getId();
        $this->deleteExercise($exercise);

        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/exercise/' . $exerciseId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'title' => 'testExercise123',
                'difficultyLevel' => 4,
                'minutes' => 30
            ]),
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Entity with id #' . $exerciseId . ' does not exist.', $responseData['message']);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testCannotUpdateWorkoutIfNotOwner(): void
    {
        $user = $this->createUser('testaccount@example.com');
        $apiToken = $this->createApiToken($user);

        $exercise = $this->createExercise();

        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/exercise/' . $exercise->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $apiToken->getToken()],
            json_encode([
                'title' => 'testExercise123',
                'difficultyLevel' => 4,
                'minutes' => 30
            ]),
        );
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Forbidden Access", $responseData['message']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
