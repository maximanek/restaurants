<?php

namespace App\Validator;

use App\Models\User;

class UserValidator
{
    public const USER_LIMIT = 3;

    public static function checkUserLimits(User $user): bool
    {
        return count($user->restaurants()->pluck('user_id')) < self::USER_LIMIT;
    }
}
