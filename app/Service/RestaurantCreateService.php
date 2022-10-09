<?php

namespace App\Service;

use App\Models\Restaurant;

class RestaurantCreateService extends Service
{
    public function create(array $data): Restaurant
    {
        $restaurant = Restaurant::create($data);

        if (key_exists('user_ids', $data)) {
            $restaurant->users()->attach($data['users_id']);
        }

        return $restaurant;
    }
}
