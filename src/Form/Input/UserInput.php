<?php

namespace App\Form\Input;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserInput
 */
class UserInput
{
    /**
     * @var string|null
     *
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 8,
     *     max = 30
     * )
     * @var string|null
     */
    private $password;

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
