<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_only_shows_google_authentication_option(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Continue with Google')
            ->assertDontSee('Email address')
            ->assertDontSee('Password');
    }

    public function test_email_password_login_endpoint_is_not_available(): void
    {
        $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ])->assertStatus(405);
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');

        $this->assertGuest();
    }
}
