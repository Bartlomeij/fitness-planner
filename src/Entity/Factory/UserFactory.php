<?php

namespace App\Entity\Factory;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory
{
    /**
     * @param string $email
     * @param string $plainPassword
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return User
     */
    public static function createNewUser(
        string $email,
        string $plainPassword,
        UserPasswordEncoderInterface $passwordEncoder
    ): User {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $passwordEncoder->encodePassword($user, $plainPassword)
        );
        return $user;
    }
}
