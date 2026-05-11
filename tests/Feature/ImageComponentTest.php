<?php

namespace Tests\Feature;

use App\Models\Image;
use Livewire\Livewire;
use Tests\TestCase;

class ImageComponentTest extends TestCase
{
    public function test_livewire_image_component_renders_with_title(): void
    {
        $image = Image::query()->create([
            'title' => 'Sunset',
            'hash' => 'hash-img-'.fake()->uuid(),
            'hide' => false,
        ]);

        Livewire::test('image', [
            'image' => $image,
        ])
            ->assertSee('Sunset')
            ->assertSee('alt="Sunset"', false)
            ->assertSee('src="'.asset('storage/images/'.$image->id.'.jpg').'"', false)
            ->assertSee("background-image: url('".asset('storage/images/previews/'.$image->id.'.jpg')."')", false);
    }

    public function test_livewire_image_component_dispatches_preview_event_on_click(): void
    {
        $image = Image::query()->create([
            'title' => null,
            'hash' => 'hash-img-'.fake()->uuid(),
            'hide' => false,
        ]);

        Livewire::test('image', [
            'image' => $image,
        ])
            ->call('preview')
            ->assertDispatched('preview', id: $image->id)
            ->assertSee('alt="'.$image->id.' image"', false);
    }
}
