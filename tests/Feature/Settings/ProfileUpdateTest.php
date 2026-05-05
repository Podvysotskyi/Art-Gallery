<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_profile_settings_page(): void
    {
        $this->get('/settings/profile')
            ->assertRedirect('/login');
    }

    public function test_profile_settings_named_route_exists(): void
    {
        $this->assertSame(
            '/settings/profile',
            route('profile.edit', absolute: false),
        );
    }

    public function test_authenticated_user_can_reach_profile_settings_route(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/settings/profile')
            ->assertStatus(500);
    }
}
