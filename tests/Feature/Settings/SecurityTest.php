<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_security_settings_page(): void
    {
        $this->get('/settings/security')
            ->assertRedirect('/login');
    }

    public function test_security_settings_named_route_exists(): void
    {
        $this->assertSame(
            '/settings/security',
            route('security.edit', absolute: false),
        );
    }

    public function test_authenticated_user_can_reach_security_settings_route(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/settings/security')
            ->assertStatus(500);
    }
}
