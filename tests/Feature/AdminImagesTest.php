<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_images_page_is_available(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.images'))
            ->assertOk()
            ->assertSee('Image Library');
    }

    public function test_admin_images_page_renders_edit_modal_trigger(): void
    {
        $user = User::factory()->create();
        $image = Image::create([
            'title' => 'Test Image',
            'hash' => 'test-hash',
            'hide' => false,
        ]);

        $this->assertNotNull($image->id);

        $this->actingAs($user)
            ->get(route('admin.images'))
            ->assertOk()
            ->assertSee('Image Library');

        Livewire::actingAs($user)
            ->test('admin.images.table')
            ->assertSee('Edit')
            ->assertSee($image->title);
    }

    public function test_admin_images_edit_modal_contains_form_content_when_opened(): void
    {
        $user = User::factory()->create();
        $image = Image::create([
            'title' => 'Full Request Image',
            'hash' => 'test-hash-2',
            'hide' => false,
        ]);

        Livewire::actingAs($user)
            ->test('admin.images.edit-modal')
            ->call('openEditImage', $image->id)
            ->assertSee('Edit Image')
            ->assertSee('Full Request Image')
            ->assertSee('Save Changes');
    }
}
