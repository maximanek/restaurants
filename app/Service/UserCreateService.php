<?php

namespace App\Service;

use App\Models\User;

class UserCreateService  extends Service
{
    public function create(array $data): User
    {
        $user = User::create($data);

        if (key_exists('restaurant_ids', $data)) {
            foreach ($data['restaurant_ids'] as $restaurantId) {
                $user->restaurants()->attach($restaurantId);
            }
        }

        return $user;
    }
}
