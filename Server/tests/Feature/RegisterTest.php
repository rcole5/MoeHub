<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class RegisterTest
 * @package Tests\Feature
 */
class RegisterTest extends TestCase
{
    /**
     * Tests that a user was successfully registered.
     */
    public function testRegisterSuccessfully()
    {
        $payload = [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'testPassword123',
            'password_confirmation' => 'testPassword123'
        ];

        $this->json('post', '/api/register', $payload)
            ->assertStatus(201)
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

    /**
     * Tests that correct errors are returned if correct info is not supplied.
     */
    public function testRegisterRequiresNameEmailPassword()
    {
        $this->json('post', '/api/register')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => [
                        "The name field is required."
                    ],
                    "email" => [
                        "The email field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ]
                ]
            ]);
    }

    /**
     * Tests that password_confirmation is required.
     */
    public function testRequirePasswordConfirmation()
    {
        $payload = [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'testPassword123'
        ];

        $this->json('post', '/api/register', $payload)
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "password" => [
                        "The password confirmation does not match."
                    ]
                ]
            ]);
    }

    /**
     * Tests that passwords have to be the same.
     */
    public function testIncorrectPasswordCombination()
    {
        $payload = [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'testPassword123',
            'password_confirmation' => 'test'
        ];

        $this->json('post', '/api/register', $payload)
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "password" => [
                        "The password confirmation does not match."
                    ]
                ]
            ]);
    }
}
