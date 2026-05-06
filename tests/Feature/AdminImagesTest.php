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
            ->assertOk();
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
            ->assertOk();

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
            ->test('admin.images.edit')
            ->call('openModal', $image->id)
            ->assertSee('Edit Image')
            ->assertSee('Full Request Image')
            ->assertSee('Save Changes');
    }

    public function test_admin_images_create_modal_contains_form_content_when_opened(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('admin.images.create')
            ->call('openModal')
            ->assertSee('New Image')
            ->assertSee('Create Image')
            ->assertSee('Image File (JPG)');
    }

    public function test_admin_images_create_component_can_hide_upload_button_trigger(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('admin.images.create', ['showTrigger' => false])
            ->assertDontSee('Upload Image')
            ->assertSee('Create Image');
    }

    public function test_admin_images_create_modal_close_dispatches_null_image_id_event(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('admin.images.create')
            ->call('openModal')
            ->call('closeModal')
            ->assertDispatched('image-created', imageId: null);
    }

    public function test_admin_images_edit_modal_delete_confirmation_and_cancel_are_available(): void
    {
        $user = User::factory()->create();
        $image = Image::create([
            'title' => 'Confirm Delete Image',
            'hash' => 'confirm-delete-image-hash',
            'hide' => false,
        ]);

        Livewire::actingAs($user)
            ->test('admin.images.edit')
            ->call('openModal', $image->id)
            ->assertSee('Delete Image')
            ->call('confirmImageDelete')
            ->assertSee('Delete image?')
            ->assertSee('Confirm Delete')
            ->call('cancelImageDelete')
            ->assertSee('Edit Image');
    }
}
