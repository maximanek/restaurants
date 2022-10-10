<?php

namespace App\Validator;

use App\Models\Restaurant;

class RestaurantValidator
{
    public const RESTAURANT_LIMIT = 5;

    public static function checkRestaurantLimits(Restaurant $restaurant): bool
    {
        return count($restaurant->users()->pluck('restaurant_id')) < self::RESTAURANT_LIMIT;
    }
}
