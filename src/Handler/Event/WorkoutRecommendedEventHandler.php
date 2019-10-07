<?php

namespace App\Handler\Event;

use App\Event\WorkoutRecommendedEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class WorkoutRecommendedEventHandler implements MessageHandlerInterface
{
    /**
     * @param WorkoutRecommendedEvent $event
     */
    public function __invoke(WorkoutRecommendedEvent $event): void
    {
        //todo Send an email
    }
}
