<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use Tests\AbstractTestCase;

class RestaurantControllerUpdateTest extends AbstractTestCase
{

    public function test_status_code(): void
    {
        $restaurant = Restaurant::first();
        $response = $this->patchJson(
            '/api/restaurants/'. $restaurant->id,
            ['name' => 'MoreAwesomeChefs'],
            ['Authorization' => $this->getToken()]
        );

        $response->assertStatus(200);
    }

    public function test_response_structure(): void
    {
        $restaurant = Restaurant::first();
        $response = $this->patchJson(
            '/api/restaurants/'. $restaurant->id,
            ['name' => 'EvenAwesomeChefs'],
            ['Authorization' => $this->getToken()]
        );

        $response->assertJsonStructure([
            'id',
            'name',
        ]);
    }

    public function test_response_data(): void
    {
        $name = 'TopChefs';
        $restaurant = Restaurant::first();
        $response = $this->patchJson(
            '/api/restaurants/'. $restaurant->id,
            ['name' => $name],
            ['Authorization' => $this->getToken()]
        );

        $restaurant = json_decode($response->getContent(), true);

        $this->assertEquals($name, $restaurant['name']);
    }

    public function test_database_presence(): void
    {
        $name = 'ChadChefs';
        $restaurant = Restaurant::first();
        $response = $this->patchJson(
            '/api/restaurants/'. $restaurant->id,
            ['name' => $name],
            ['Authorization' => $this->getToken()]
        );

        $id = json_decode($response->getContent(), true)['id'];

        $this->assertDatabaseHas('restaurants', [
            'id' => $id,
            'name' => $name
        ]);
    }
}
