<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

/**
 * Class LoginTest
 * @package Tests\Feature
 */
class LoginTest extends TestCase
{
    /**
     * Test that the correct errors are returned if email or password is not supplied.
     */
    public function testUserLoginRequireInfo()
    {

        $this->json('POST', '/api/login')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."]
                ]
            ]);
    }

    /**
     * Tests that a user is successfully logged in.
     */
    public function testLoginSuccess()
    {
        $user = factory(User::class)->create([
            'email' => 'test@login.com',
            'password' => bcrypt('testPassword123')
        ]);

        $payload = [
            'email' => 'test@login.com',
            'password' => 'testPassword123'
        ];

        $this->json('POST', '/api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                    'api_token'
                ]
            ]);
    }
}
