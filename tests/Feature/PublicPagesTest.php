<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Story;
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

    public function test_gallery_page_only_shows_visible_stories_and_images(): void
    {
        $visibleStory = Story::query()->create([
            'title' => 'Visible Story',
            'hide' => false,
        ]);

        Story::query()->create([
            'title' => 'Hidden Story',
            'hide' => true,
        ]);

        Image::query()->create([
            'title' => 'Visible Image',
            'hash' => 'visible-image-hash',
            'hide' => false,
        ]);

        Image::query()->create([
            'title' => 'Hidden Image',
            'hash' => 'hidden-image-hash',
            'hide' => true,
        ]);

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee("Story {$visibleStory->id}: {$visibleStory->title}")
            ->assertDontSee('Hidden Story')
            ->assertSee('Visible Image')
            ->assertDontSee('Hidden Image');
    }

    public function test_projects_page_is_available(): void
    {
        $this->get(route('projects'))
            ->assertOk();
    }
}
