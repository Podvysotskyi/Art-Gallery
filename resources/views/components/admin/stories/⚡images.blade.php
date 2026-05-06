<?php

use App\Models\Image;
use App\Models\Story;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?Story $story = null;

    #[On('open-story-images')]
    public function openModal(string $storyId): void
    {
        $this->story = Story::query()
            ->with('images')
            ->find($storyId);

        if ($this->story) {
            Flux::modal('story-images')->show();
        }
    }

    public function closeModal(): void
    {
        Flux::modal('story-images')->close();
    }

    public function createImage(): void
    {
        Flux::modal('story-images')->close();
        $this->dispatch('create-image');
    }

    public function editImage(string $imageId): void
    {
        Flux::modal('story-images')->close();
        $this->dispatch('edit-image', imageId: $imageId);
    }

    #[On('image-updated')]
    #[On('image-created')]
    public function saveImage(?string $imageId = null): void
    {
        if ($imageId) {
            $image = Image::query()->find($imageId);
            $image->entity()->associate($this->story);
            $image->save();
        }

        $this->updateImages();
    }

    #[On('image-deleted')]
    public function updateImages(): void
    {
        $this->story->load('images');
        Flux::modal('story-images')->show();
    }
};
?>

<div>
    <flux:modal name="story-images" class="w-full max-w-5xl" :dismissible="false" :closable="false">
        <section class="space-y-5 text-left">
            <div>
                <flux:heading size="lg">Story Images</flux:heading>
                <flux:text class="mt-1">
                    @if($this->story)
                        {{ $this->story->title }}
                    @endif
                </flux:text>
            </div>

            @if($this->story)
                <div class="overflow-hidden rounded-md border border-zinc-200">
                    <table class="min-w-full divide-y divide-zinc-200">
                        <thead class="bg-zinc-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                                Preview
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                                Title
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">
                                Status
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-600">
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 bg-white">
                        @forelse($this->story->images as $image)
                            <tr>
                                <td class="px-4 py-3">
                                    <img
                                        src="{{ asset('storage/images/previews/'.$image->id.'.jpg') }}"
                                        alt="{{ $image->title ?: 'Image preview' }}"
                                        class="h-12 w-12 rounded-md bg-zinc-100 object-cover"
                                        loading="lazy"
                                    />
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-800">{{ $image->title ?: 'Untitled' }}</td>
                                <td class="px-4 py-3 text-sm text-zinc-600">{{ $image->hide ? 'Hidden' : 'Published' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button
                                        type="button"
                                        wire:click="editImage('{{ $image->id }}')"
                                        class="rounded-md border border-zinc-300 px-3 py-1.5 text-sm font-medium text-zinc-700 transition hover:border-zinc-400 hover:text-zinc-900"
                                    >
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-sm text-zinc-500">
                                    No images attached to this story.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <flux:button variant="ghost" type="button" wire:click="closeModal">
                        Cancel
                    </flux:button>

                    <flux:button variant="primary" type="button" wire:click="createImage">
                        Create Image
                    </flux:button>
                </div>
            @endif
        </section>
    </flux:modal>

    <livewire:admin.images.create :show-trigger="false"/>
    <livewire:admin.images.edit/>
</div>
