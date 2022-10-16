<?php

namespace App\Service;

use App\Models\Restaurant;
use App\Exceptions\RestaurantNotFoundException;

class RestaurantUpdateService
{
    /**
     * @throws RestaurantNotFoundException
     */
    public function update(Restaurant $restaurant, array $data): Restaurant
    {
        $success = $restaurant->update($data);

        if (!$success) {
            throw new RestaurantNotFoundException();
        }

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
