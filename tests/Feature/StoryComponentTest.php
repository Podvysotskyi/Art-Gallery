<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StoryComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_livewire_story_component_renders_story_with_metadata_and_images(): void
    {
        $story = Story::query()->create([
            'title' => 'Gallery Story',
            'subtitle' => 'Seasonal Collection',
            'description' => 'A curated set of photographs.',
            'hide' => false,
        ]);

        $image = Image::query()->create([
            'title' => 'Cover',
            'hash' => 'hash-story-cover',
            'hide' => false,
        ]);
        $story->images()->save($image);

        Livewire::test('story', [
            'story' => $story,
        ])
            ->assertSee('Gallery Story')
            ->assertSee('Seasonal Collection')
            ->assertSee('A curated set of photographs.')
            ->assertSee('alt="Cover"', false)
            ->assertSee('src="'.asset('storage/images/previews/'.$image->id.'.jpg').'"', false);
    }

    public function test_livewire_story_component_hides_optional_sections_when_empty(): void
    {
        $story = Story::query()->create([
            'title' => 'Minimal Story',
            'subtitle' => null,
            'description' => null,
            'hide' => false,
        ]);

        Livewire::test('story', [
            'story' => $story,
        ])
            ->assertSee('Minimal Story')
            ->assertDontSee('text-sm text-gray-600', false)
            ->assertDontSee('text-sm font-thin', false);
    }
}
