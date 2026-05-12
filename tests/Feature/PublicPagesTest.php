<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
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
            ->assertSee($visibleStory->title)
            ->assertDontSee('Hidden Story')
            ->assertSee('Visible Image')
            ->assertDontSee('Hidden Image');
    }

    public function test_gallery_page_can_filter_images_by_story(): void
    {
        $story = Story::query()->create([
            'title' => 'Filtered Story',
            'hide' => false,
        ]);

        $storyImage = Image::query()->create([
            'title' => 'Story Image',
            'hash' => 'story-image-hash',
            'hide' => false,
        ]);
        $story->images()->save($storyImage);

        Image::query()->create([
            'title' => 'Other Image',
            'hash' => 'other-image-hash',
            'hide' => false,
        ]);

        $this->get(route('gallery', ['story' => $story->id]))
            ->assertOk()
            ->assertSee('Story Image')
            ->assertDontSee('Other Image');
    }

    public function test_gallery_livewire_component_renders_visible_content(): void
    {
        Story::query()->create([
            'title' => 'Livewire Story',
            'hide' => false,
        ]);

        Image::query()->create([
            'title' => 'Livewire Image',
            'hash' => 'livewire-image-hash',
            'hide' => false,
        ]);

        Livewire::test('pages::gallery')
            ->assertSee('Livewire Story')
            ->assertSee('Livewire Image');
    }

    public function test_gallery_livewire_component_filters_images_when_story_is_selected(): void
    {
        $story = Story::query()->create([
            'title' => 'Selected Story',
            'hide' => false,
        ]);

        $storyImage = Image::query()->create([
            'title' => 'Selected Story Image',
            'hash' => 'selected-story-image-hash',
            'hide' => false,
        ]);
        $story->images()->save($storyImage);

        Image::query()->create([
            'title' => 'Unselected Image',
            'hash' => 'unselected-image-hash',
            'hide' => false,
        ]);

        Livewire::test('pages::gallery')
            ->assertSee('Selected Story Image')
            ->assertSee('Unselected Image')
            ->call('selectStory', $story->id)
            ->assertSet('selectedStoryId', $story->id)
            ->tap(function ($component): void {
                $images = $component->get('images');

                $this->assertTrue($images->contains('title', 'Selected Story Image'));
                $this->assertFalse($images->contains('title', 'Unselected Image'));
            });
    }

    public function test_projects_page_is_available(): void
    {
        $this->get(route('projects'))
            ->assertOk();
    }
}
