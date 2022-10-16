<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $MeatChefs = Restaurant::create(['name' => 'MeatChefs']);
        $VegeChefs = Restaurant::create(['name' => 'VegeChefs']);
        $BurgerChefs = Restaurant::create(['name' => 'BurgerChefs']);

        $restaurants = [$MeatChefs, $VegeChefs, $BurgerChefs];
        $users = User::all()->collect();

        while (true) {
            $restaurantKey = rand(0, 2);
            $userKey = rand(0, 9);

            if (key_exists($restaurantKey, $restaurants)) {
                if (count($restaurants[$restaurantKey]->users()->pluck('user_id')) < 5) {
                    if (key_exists($userKey, $users->toArray())) {
                        if (count($users[$userKey]->restaurants()->pluck('user_id')) < 3) {
                            $restaurants[$restaurantKey]->users()->attach($users[$userKey]);
                        } else {
                            $users->forget($userKey);
                        }
                    }
                    continue;
                } else {
                    unset($restaurants[$restaurantKey]);
                }
            }

            if(count($restaurants)) {
                continue;
            }
            break;
        }
    }
}
