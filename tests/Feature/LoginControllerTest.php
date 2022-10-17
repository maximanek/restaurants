<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\AbstractTestCase;

class LoginControllerTest extends AbstractTestCase
{

    public function test_status_code(): void
    {
        $user = User::first();
        $response = $this->postJson(
            route('api.login'),
            [
                'email' => $user->email,
                'password' => 'password'
            ]
        );

        $response->assertStatus(200);
    }
}
