<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserQueryService
 * @package App\Service
 */
class UserQueryService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserQueryService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->userRepository = $entityManager->getRepository(User::class);
    }

    /**
     * @param int $userId
     * @return User|null
     */
    public function findUserById(int $userId): ?User
    {
        return $this->userRepository->find($userId);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy([
            'email' => $email
        ]);
    }
}
