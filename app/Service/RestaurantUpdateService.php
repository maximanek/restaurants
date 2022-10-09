<?php

namespace App\Service;

use App\Models\Restaurant;

class RestaurantUpdateService
{
    public function update(Restaurant $restaurant, array $data): Restaurant
    {
        $restaurant->update($data);

        if (key_exists('users', $data)) {
            foreach ($restaurant->users() as $user) {
                $restaurant->users()->detach($user);
            }

            foreach ($data['users'] as $userId) {
                $restaurant->users()->attach($userId);
            }
        }

        return $restaurant;
    }
}
