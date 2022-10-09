<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    private array $restaurants;
    private Collection $users;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $MeatChefs = Restaurant::create(['name' => 'MeatChefs']);
        $VegeChefs = Restaurant::create(['name' => 'VegeChefs']);
        $BurgerChefs = Restaurant::create(['name' => 'BurgerChefs']);

        $this->restaurants = [$MeatChefs, $VegeChefs, $BurgerChefs];
        $this->users = User::all()->collect();

        while (true) {
            $restaurantKey = rand(0, 2);
            $userKey = rand(0, 9);

            if (key_exists($restaurantKey, $this->restaurants)) {
                if (count($this->restaurants[$restaurantKey]->users()->pluck('user_id')) < 5) {
                    if (key_exists($userKey, $this->users->toArray())) {
                        if (count($this->users[$userKey]->restaurants()->pluck('user_id')) < 3) {
                            $this->restaurants[$restaurantKey]->users()->attach($this->users[$userKey]);
                        } else {
                            $this->users->forget($userKey);
                        }
                    }
                    continue;
                } else {
                    unset($this->restaurants[$restaurantKey]);
                }
            }

            if(count($this->restaurants)) {
                continue;
            }
            break;
        }


    }
}
