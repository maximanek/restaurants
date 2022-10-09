<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserCreateService  extends Service
{
    public function create(array $data): User
    {
        $user = User::create($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        if (key_exists('restaurant_ids', $data)) {
            foreach ($data['restaurant_ids'] as $restaurantId) {
                $user->restaurants()->attach($restaurantId);
            }
        }

        return $user;
    }
}
