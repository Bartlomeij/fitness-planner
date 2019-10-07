<?php

namespace App\Form\Input;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class WorkoutInput
 */
class WorkoutInput
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max = 255
     * )
     * @var string|null
     */
    private $title;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }
}
