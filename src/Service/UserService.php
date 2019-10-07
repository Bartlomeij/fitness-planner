<?php

namespace App\Service;

use App\Entity\Factory\UserFactory;
use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param string $email
     * @param string $plainPassword
     * @throws UserAlreadyExistsException
     */
    public function createUser(string $email, string $plainPassword): void
    {
        if ($this->entityManager->getRepository(User::class)->findOneBy(['email' => $email])) {
            throw new UserAlreadyExistsException(sprintf('User with email %s already exists!', $email));
        }

        $user = UserFactory::createNewUser(
            $email,
            $plainPassword,
            $this->passwordEncoder,
        );
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param int $userId
     * @throws EntityNotFoundException
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if (!$user instanceof User) {
            throw new EntityNotFoundException('Entity #' . $userId . ' not found');
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
