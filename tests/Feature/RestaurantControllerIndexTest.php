<?php

namespace Tests\Feature;

use Tests\AbstractTestCase;

class RestaurantControllerIndexTest extends AbstractTestCase
{
    public function test_status_code(): void
    {
        $response = $this->getJson('/api/restaurants', ['Authorization' => $this->getToken()]);

        $response->assertStatus(200);
    }

    public function test_pagination(): void
    {
        $response = $this->getJson('/api/restaurants/?limit=2', ['Authorization' => $this->getToken()]);
        $user = json_decode($response->getContent())->data[1];

        $response = $this->getJson('/api/restaurants/?page=2&limit=1', ['Authorization' => $this->getToken()]);
        $secondUser = json_decode($response->getContent())->data[0];

        $this->assertSame($user->id, $secondUser->id);
    }
}
