<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AbstractTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function getToken(): string
    {
        $user = User::first();

        $response = $this->postJson(
            '/api/login',
            [
                'email' => $user->email,
                'password' => 'password'
            ]
        );
        $token = json_decode($response->getContent());

        return "Bearer $token";
    }
}
