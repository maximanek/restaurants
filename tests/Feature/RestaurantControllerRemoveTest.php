<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use Tests\AbstractTestCase;

class RestaurantControllerRemoveTest extends AbstractTestCase
{
    private ?Restaurant $restaurant;

    public function test_status_code(): void
    {
        $this->restaurant = Restaurant::first();
        $restaurant = $this->deleteJson(
            uri: '/api/restaurants/' . $this->restaurant->id,
            headers: ['Authorization' => $this->getToken()]
        );

        $restaurant->assertStatus(200);
    }

    public function testResponseEmpty(): void
    {
        $this->restaurant = Restaurant::first();
        $restaurant = $this->deleteJson(
            uri: '/api/restaurants/' . $this->restaurant->id,
            headers: ['Authorization' => $this->getToken()]
        );

        $this->assertSame($restaurant->getContent(), '{}');
    }

    public function testDatabaseDeleteRow(): void
    {
        $this->restaurant = Restaurant::first();
        $id = $this->restaurant->id;
         $this->deleteJson(
            uri: '/api/restaurants/' . $id,
            headers: ['Authorization' => $this->getToken()]
        );

        $this->assertDatabaseMissing('restaurants', ['id' =>  $id]);
    }
}
