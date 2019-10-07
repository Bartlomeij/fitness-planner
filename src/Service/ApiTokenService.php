<?php

namespace App\Service;

use App\Entity\ApiToken;
use App\Entity\Factory\ApiTokenFactory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ApiTokenService
 * @package App\Service
 */
class ApiTokenService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ApiTokenService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @return ApiToken
     */
    public function createApiToken(User $user): ApiToken
    {
        $apiToken = ApiTokenFactory::createNewApiToken($user);
        $this->entityManager->persist($apiToken);
        $this->entityManager->flush();
        return $apiToken;
    }
}
