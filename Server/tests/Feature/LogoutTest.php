<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

/**
 * Class LogoutTest
 * @package Tests\Feature
 */
class LogoutTest extends TestCase
{

    /**
     * Test that the user is successfully logged out.
     */
    public function testUserIsLoggedOut()
    {
        $user = factory(User::class)->create([
            'email' => 'test@test.com'
        ]);

        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];

        $this->json('get', '/api/user', [], $headers)
            ->assertStatus(200);
        $this->json('post', '/api/logout', [], $headers)
            ->assertStatus(200);

        $user = User::find($user->id);
        $this->assertEquals(null, $user->api_token);
    }

    /**
     * Test that user token is null.
     */
    public function testUserNullToken()
    {
        $user = factory(User::class)->create(['email' => 'user@test.com']);
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];

        $user->api_token = null;
        $user->save();

        $this->json('get', '/api/user', [], $headers)->assertStatus(401);
    }
}
