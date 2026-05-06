<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Mockery;
use Spatie\Image\Image as SpatieImage;
use Tests\TestCase;

class AuthenticatedNavigationTest extends TestCase
{
    use RefreshDatabase;

    private function mockPreviewGeneration(): void
    {
        $preview = Mockery::mock();
        $preview->shouldReceive('fit')->andReturnSelf();
        $preview->shouldReceive('save')->andReturnSelf();

        Mockery::mock('alias:'.SpatieImage::class)
            ->shouldReceive('load')
            ->andReturn($preview);
    }

    public function test_guest_is_redirected_from_admin(): void
    {
        $this->get('/admin')
            ->assertRedirect('/login');
    }

    public function test_authenticated_user_is_redirected_from_admin_to_images_section(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertRedirect('/admin/images');
    }

    public function test_guest_is_redirected_from_admin_images_page(): void
    {
        $this->get('/admin/images')
            ->assertRedirect('/login');
    }

    public function test_guest_is_redirected_from_admin_images_create_route(): void
    {
        $this->get('/admin/images/create')
            ->assertRedirect('/login');
    }

    public function test_guest_is_redirected_from_admin_projects_page(): void
    {
        $this->get('/admin/projects')
            ->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_admin_images_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/images')
            ->assertOk();
    }

    public function test_authenticated_user_can_view_admin_projects_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/projects')
            ->assertOk()
            ->assertSee('Projects')
            ->assertSee('Project Library');
    }

    public function test_admin_projects_page_shows_projects_from_database(): void
    {
        $firstProject = Project::query()->create([
            'title' => 'Spring Collection',
            'hide' => false,
        ]);

        $secondProject = Project::query()->create([
            'title' => 'Private Commission',
            'hide' => true,
        ]);

        Livewire::actingAs(User::factory()->create())
            ->test('admin.projects-table')
            ->assertSee($firstProject->title)
            ->assertSee($secondProject->title)
            ->assertSee('Published')
            ->assertSee('Hidden');
    }

    public function test_admin_images_page_shows_image_records_from_database(): void
    {
        $firstImage = Image::query()->create([
            'title' => 'Sunset Over Harbor',
            'hash' => 'img-hash-1',
            'hide' => false,
        ]);

        $secondImage = Image::query()->create([
            'title' => 'Untitled Composition',
            'hash' => 'img-hash-2',
            'hide' => true,
        ]);

        Livewire::actingAs(User::factory()->create())
            ->test('admin.images.table')
            ->assertSee($firstImage->title)
            ->assertSee($secondImage->title)
            ->assertSee('Published')
            ->assertSee('Hidden');
    }

    public function test_authenticated_user_can_view_admin_images_page_with_create_action(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test('admin.images.create')
            ->assertSee('Upload Image');
    }

    public function test_authenticated_user_can_view_admin_image_edit_modal_content(): void
    {
        $image = Image::query()->create([
            'title' => 'Editable Image',
            'hash' => 'editable-hash',
            'hide' => false,
        ]);

        Livewire::actingAs(User::factory()->create())
            ->test('admin.images.table')
            ->assertSee('Edit');

        Livewire::actingAs(User::factory()->create())
            ->test('admin.images.edit')
            ->call('openModal', $image->id)
            ->assertSee('Edit Image')
            ->assertSee('Save Changes')
            ->assertSee('Delete Image')
            ->assertSee('Image Name');
    }

    public function test_authenticated_user_can_create_image_record(): void
    {
        Storage::fake('public');
        $this->mockPreviewGeneration();
        $image = UploadedFile::fake()->image('blue-dusk.jpg');
        $expectedHash = hash_file('sha256', $image->getRealPath());

        Livewire::actingAs(User::factory()->create())
            ->test('admin.images.create')
            ->set('title', 'Blue Dusk')
            ->set('image', $image)
            ->set('hide', true)
            ->call('createImage');

        $this->assertDatabaseHas('images', [
            'title' => 'Blue Dusk',
            'hide' => true,
        ]);

        $storedImage = Image::query()->where('title', 'Blue Dusk')->firstOrFail();
        $expectedFileName = $storedImage->id.'.jpg';

        $this->assertSame($expectedHash, $storedImage->hash);
        Storage::disk('public')->assertExists('images/'.$expectedFileName);
    }

    public function test_image_filename_is_used_as_title_when_title_is_empty(): void
    {
        Storage::fake('public');
        $this->mockPreviewGeneration();
        $image = UploadedFile::fake()->image('auto-title.jpg');

        Livewire::actingAs(User::factory()->create())
            ->test('admin.images.create')
            ->set('title', '')
            ->set('image', $image)
            ->call('createImage');

        $this->assertDatabaseHas('images', [
            'title' => 'auto-title',
            'hide' => true,
        ]);
    }

    public function test_only_jpg_files_are_accepted_for_image_upload(): void
    {
        Storage::fake('public');
        $pngImage = UploadedFile::fake()->image('not-allowed.png');

        Livewire::actingAs(User::factory()->create())
            ->test('admin.images.create')
            ->set('title', 'PNG Attempt')
            ->set('image', $pngImage)
            ->call('createImage')
            ->assertHasErrors('image');
    }

    public function test_uploaded_jpeg_files_are_stored_with_jpg_extension(): void
    {
        Storage::fake('public');
        $this->mockPreviewGeneration();
        $jpegImage = UploadedFile::fake()->create('converted-name.jpeg', 50, 'image/jpeg');
        $expectedHash = hash_file('sha256', $jpegImage->getRealPath());

        Livewire::actingAs(User::factory()->create())
            ->test('admin.images.create')
            ->set('title', 'JPEG Upload')
            ->set('image', $jpegImage)
            ->call('createImage');

        $storedImage = Image::query()->where('title', 'JPEG Upload')->firstOrFail();

        $this->assertSame($expectedHash, $storedImage->hash);
        Storage::disk('public')->assertExists('images/'.$storedImage->id.'.jpg');
        Storage::disk('public')->assertMissing('images/'.$storedImage->id.'.jpeg');
    }

    public function test_authenticated_user_can_update_image_name_and_status(): void
    {
        $image = Image::query()->create([
            'title' => 'Original Title',
            'hash' => 'original-hash',
            'hide' => false,
        ]);

        Livewire::actingAs(User::factory()->create())
            ->test('admin.images.edit')
            ->call('openModal', $image->id)
            ->set('title', 'Updated Title')
            ->set('hide', true)
            ->call('updateImage')
            ->assertDispatched('image-updated');

        $this->assertDatabaseHas('images', [
            'id' => $image->id,
            'title' => 'Updated Title',
            'hide' => true,
        ]);
    }

    public function test_authenticated_user_can_delete_image_record(): void
    {
        Storage::fake('public');

        $image = Image::query()->create([
            'title' => 'Delete Target',
            'hash' => 'delete-hash',
            'hide' => false,
        ]);

        Storage::disk('public')->put('images/'.$image->id.'.jpg', 'fake-image-content');
        Storage::disk('public')->put('images/previews/'.$image->id.'.jpg', 'fake-preview-content');

        Livewire::actingAs(User::factory()->create())
            ->test('admin.images.edit')
            ->call('openModal', $image->id)
            ->call('deleteImage')
            ->assertDispatched('image-deleted');

        $this->assertDatabaseMissing('images', [
            'id' => $image->id,
        ]);

        Storage::disk('public')->assertMissing('images/'.$image->id.'.jpg');
        Storage::disk('public')->assertMissing('images/previews/'.$image->id.'.jpg');
    }

    public function test_guest_is_redirected_from_settings(): void
    {
        $this->get('/settings')
            ->assertRedirect('/login');
    }
}
