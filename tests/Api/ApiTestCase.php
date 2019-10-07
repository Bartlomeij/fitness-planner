<?php

namespace App\Tests\Api;

use App\Entity\ApiToken;
use App\Entity\Exercise;
use App\Entity\Factory\ApiTokenFactory;
use App\Entity\Factory\ExerciseFactory;
use App\Entity\Factory\UserFactory;
use App\Entity\Factory\WorkoutFactory;
use App\Entity\User;
use App\Entity\Workout;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ApiTestCase
 * @package App\Tests\Api
 */
class ApiTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        $this->truncateEntities([
            ApiToken::class,
            Exercise::class,
            User::class,
            Workout::class,
        ]);
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function createUser(string $email, string $password = 'secretPassword'): User
    {
        $user = UserFactory::createNewUser(
            $email,
            $password,
            $this->getContainer()->get('security.password_encoder'),
        );

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
        return $user;
    }

    /**
     * @param User $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function deleteUser(User $user): void
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }

    /**
     * @param User $user
     * @return ApiToken
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function createApiToken(User $user): ApiToken
    {
        $apiToken = ApiTokenFactory::createNewApiToken($user);

        $em = $this->getEntityManager();
        $em->persist($apiToken);
        $em->flush();
        return $apiToken;
    }

    /**
     * @param string $title
     * @param User $user
     * @return Workout
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function createWorkout(string $title = "Superhero's Workout", User $user = null): Workout
{
    if (!$user) {
        $user = $this->createUser("test+" . uniqid() . '@example.com');
    }
    $workout = WorkoutFactory::createNewWorkout($title, $user);

    $em = $this->getEntityManager();
    $em->persist($workout);
    $em->flush();
    return $workout;
}

    /**
     * @param Workout $workout
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function deleteWorkout(Workout $workout): void
    {
        $em = $this->getEntityManager();
        $em->remove($workout);
        $em->flush();
    }

    /**
     * @param string $title
     * @param int $difficultyLevel
     * @param int $minutes
     * @param User|null $user
     * @return Exercise
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function createExercise(
        string $title = "Superhero's Workout",
        int $difficultyLevel = 5,
        int $minutes = 30,
        User $user = null
    ): Exercise {
        if (!$user) {
            $user = $this->createUser("test+" . uniqid() . '@example.com');
        }
        $exercise = ExerciseFactory::createNewExercise(
            $title,
            $difficultyLevel,
            $minutes,
            $user
        );

        $em = $this->getEntityManager();
        $em->persist($exercise);
        $em->flush();
        return $exercise;
    }

    /**
     * @param Exercise $exercise
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function deleteExercise(Exercise $exercise): void
    {
        $em = $this->getEntityManager();
        $em->remove($exercise);
        $em->flush();
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @return ContainerInterface
     */
    private function getContainer(): ContainerInterface
    {
        return self::$kernel->getContainer();
    }

    private function truncateEntities(array $entities)
    {
        $connection = $this->getEntityManager()->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();

        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }

        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $this->getEntityManager()->getClassMetadata($entity)->getTableName()
            );

            $connection->executeUpdate($query);
        }

        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
