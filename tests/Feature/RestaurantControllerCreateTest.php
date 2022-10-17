<?php

namespace Tests\Feature;

use Tests\AbstractTestCase;

class RestaurantControllerCreateTest extends AbstractTestCase
{

    public function test_status_code(): void
    {
        $response = $this->postJson(
            route('api.restaurants.store'),
            ['name' => 'AwesomeChefs'],
            ['Authorization' => $this->getToken()]
        );

        $response->assertStatus(201);
    }

    public function test_response_structure(): void
    {
        $response = $this->postJson(
            route('api.restaurants.store'),
            ['name' => 'AwesomeChefs'],
            ['Authorization' => $this->getToken()]
        );

        $response->assertJsonStructure([
            'id',
            'name',
        ]);
    }

    public function test_response_data(): void
    {
        $name = 'AwesomeChefs';

        $response = $this->postJson(
            route('api.restaurants.store'),
            ['name' => $name],
            ['Authorization' => $this->getToken()]
        );

        $restaurant = json_decode($response->getContent(), true);

        $this->assertEquals($name, $restaurant['name']);
    }

    public function test_database_presence(): void
    {
        $name = 'AwesomeChefs';

        $response = $this->postJson(
            route('api.restaurants.store'),
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
