<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_redirects_to_gallery(): void
    {
        $this->get('/')
            ->assertRedirect('/gallery');
    }

    public function test_gallery_page_is_available(): void
    {
        $this->get(route('gallery'))
            ->assertOk();
    }

    public function test_projects_page_is_available(): void
    {
        $this->get(route('projects'))
            ->assertOk();
    }
}
