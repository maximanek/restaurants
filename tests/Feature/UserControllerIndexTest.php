<?php

namespace Tests\Feature;

use Tests\AbstractTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerIndexTest extends AbstractTestCase
{
    use RefreshDatabase;

    public function test_status_code(): void
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    public function test_pagination(): void
    {
        $response = $this->get('/api/users/?limit=2');
        $user = json_decode($response->getContent())->data[1];

        $response = $this->get('/api/users/?page=2&limit=1');
        $secondUser = json_decode($response->getContent())->data[0];

        $this->assertSame($user->id, $secondUser->id);
    }
}
