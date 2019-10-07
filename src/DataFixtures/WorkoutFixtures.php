<?php

namespace App\DataFixtures;

use App\Entity\Workout;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class WorkoutFixtures
 * @package App\DataFixtures
 */
class WorkoutFixtures extends BaseFixtures
{
    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            20,
            'main_workouts',
            function () {
                $workout = new Workout();
                $workout->setTitle($this->faker->name() . "'s Workout ");
                $workout->setUser($this->getRandomReference('main_users'));
                return $workout;
            }
        );
        $manager->flush();
    }
}
