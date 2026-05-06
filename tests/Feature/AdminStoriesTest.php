<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Story;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminStoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_stories_page_is_available(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.stories'))
            ->assertOk()
            ->assertSee('Story Library');
    }

    public function test_admin_stories_table_renders_stories(): void
    {
        $user = User::factory()->create();
        $story = Story::create([
            'title' => 'Test Story',
            'subtitle' => 'Test Subtitle',
            'description' => 'Test Description',
            'hide' => false,
        ]);

        Livewire::actingAs($user)
            ->test('admin.stories.table')
            ->assertSee('Test Story')
            ->assertSee('Test Subtitle');
    }

    public function test_admin_can_open_story_images_modal_and_see_images(): void
    {
        $user = User::factory()->create();
        $story = Story::create([
            'title' => 'Story With Images',
            'subtitle' => 'Subtitle',
            'description' => 'Description',
            'hide' => false,
        ]);

        $story->images()->create([
            'title' => 'First Story Image',
            'hash' => 'story-image-1',
            'hide' => false,
        ]);

        $story->images()->create([
            'title' => 'Second Story Image',
            'hash' => 'story-image-2',
            'hide' => true,
        ]);

        Livewire::actingAs($user)
            ->test('admin.stories.images')
            ->call('openModal', $story->id)
            ->assertSee('Story Images')
            ->assertSee('First Story Image')
            ->assertSee('Second Story Image')
            ->assertSee('Published')
            ->assertSee('Hidden');
    }

    public function test_admin_stories_page_full_request(): void
    {
        $user = User::factory()->create();
        Story::create([
            'title' => 'Full Request Story',
            'subtitle' => 'Full Request Subtitle',
            'description' => 'Full Request Description',
            'hide' => false,
        ]);

        $response = $this->actingAs($user)
            ->get(route('admin.stories'));

        $response->assertOk();
        $response->assertSee('Story Library');
        // Since it's lazy loaded, we might see the placeholder first in the initial response
        $response->assertSee('Loading stories...');
    }

    public function test_admin_stories_page_shows_create_story_action(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.stories'))
            ->assertOk()
            ->assertSee('New Story');
    }

    public function test_admin_can_create_story(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('admin.stories.create')
            ->set('title', 'Brand New Story')
            ->set('subtitle', 'Brand New Subtitle')
            ->set('description', 'Brand New Description')
            ->call('createStory')
            ->assertDispatched('story-updated');

        $this->assertDatabaseHas('stories', [
            'title' => 'Brand New Story',
            'subtitle' => 'Brand New Subtitle',
            'description' => 'Brand New Description',
        ]);
    }

    public function test_admin_stories_edit_page_is_available(): void
    {
        $user = User::factory()->create();
        $story = Story::create([
            'title' => 'Edit Me',
            'subtitle' => 'Old Subtitle',
            'description' => 'Old Description',
            'hide' => false,
        ]);

        $this->actingAs($user)
            ->get(route('admin.stories'))
            ->assertOk()
            ->assertSee('Story Library');

        Livewire::actingAs($user)
            ->test('admin.stories.edit')
            ->call('openModal', $story->id)
            ->assertSee('Edit Story')
            ->assertSee('Title')
            ->assertSee('Save Changes')
            ->assertSee('Delete Story');
    }

    public function test_admin_can_update_story(): void
    {
        $user = User::factory()->create();
        $story = Story::create([
            'title' => 'Old Title',
            'subtitle' => 'Old Subtitle',
            'description' => 'Old Description',
            'hide' => false,
        ]);

        Livewire::actingAs($user)
            ->test('admin.stories.edit')
            ->call('openModal', $story->id)
            ->set('title', 'Updated Title')
            ->set('subtitle', 'Updated Subtitle')
            ->set('description', 'Updated Description')
            ->set('hide', true)
            ->call('updateStory')
            ->assertDispatched('story-updated');

        $this->assertDatabaseHas('stories', [
            'id' => $story->id,
            'title' => 'Updated Title',
            'subtitle' => 'Updated Subtitle',
            'description' => 'Updated Description',
            'hide' => true,
        ]);
    }

    public function test_admin_can_delete_story(): void
    {
        $user = User::factory()->create();
        $story = Story::create([
            'title' => 'Delete Me',
            'subtitle' => 'Subtitle',
            'description' => 'Description',
            'hide' => false,
        ]);

        Livewire::actingAs($user)
            ->test('admin.stories.edit')
            ->call('openModal', $story->id)
            ->call('deleteStory')
            ->assertDispatched('story-deleted');

        $this->assertDatabaseMissing('stories', [
            'id' => $story->id,
        ]);
    }

    public function test_story_images_modal_dispatches_create_image_event(): void
    {
        $user = User::factory()->create();
        $story = Story::create([
            'title' => 'Story For Create Event',
            'subtitle' => 'Subtitle',
            'description' => 'Description',
            'hide' => false,
        ]);

        Livewire::actingAs($user)
            ->test('admin.stories.images')
            ->call('openModal', $story->id)
            ->call('createImage')
            ->assertDispatched('create-image');
    }

    public function test_story_images_modal_dispatches_edit_image_event_with_image_id(): void
    {
        $user = User::factory()->create();
        $story = Story::create([
            'title' => 'Story For Edit Event',
            'subtitle' => 'Subtitle',
            'description' => 'Description',
            'hide' => false,
        ]);
        $image = $story->images()->create([
            'title' => 'Editable Story Image',
            'hash' => 'story-edit-image',
            'hide' => false,
        ]);

        Livewire::actingAs($user)
            ->test('admin.stories.images')
            ->call('openModal', $story->id)
            ->call('editImage', $image->id)
            ->assertDispatched('edit-image');
    }

    public function test_story_images_modal_associates_new_image_to_story(): void
    {
        $user = User::factory()->create();
        $story = Story::create([
            'title' => 'Story For Image Association',
            'subtitle' => 'Subtitle',
            'description' => 'Description',
            'hide' => false,
        ]);
        $image = Image::create([
            'title' => 'Unassigned Image',
            'hash' => 'unassigned-image-hash',
            'hide' => false,
        ]);

        Livewire::actingAs($user)
            ->test('admin.stories.images')
            ->call('openModal', $story->id)
            ->call('saveImage', $image->id);

        $this->assertDatabaseHas('images', [
            'id' => $image->id,
            'entity_id' => $story->id,
            'entity_type' => Story::class,
        ]);
    }
}
