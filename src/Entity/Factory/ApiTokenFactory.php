<?php

namespace App\Entity\Factory;

use App\Entity\ApiToken;
use App\Entity\User;

class ApiTokenFactory
{
    /**
     * @param User $user
     * @return ApiToken
     */
    public static function createNewApiToken(User $user): ApiToken
    {
        return new ApiToken($user);
    }
}
