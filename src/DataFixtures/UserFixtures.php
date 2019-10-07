<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends BaseFixtures
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'main_users', function () use ($manager) {
            $user = new User();
            $user->setEmail($this->faker->safeEmail);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'secret'
            ));

            $apiToken1 = new ApiToken($user);
            $apiToken2 = new ApiToken($user);
            $manager->persist($apiToken1);
            $manager->persist($apiToken2);

            return $user;
        });
        $manager->flush();
    }
}
