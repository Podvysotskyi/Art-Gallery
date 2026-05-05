<?php

use App\Models\Story;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    public ?Story $story = null;

    #[Validate]
    public string $title = '';

    #[Validate]
    public string $subtitle = '';

    #[Validate]
    public string $description = '';

    #[Validate]
    public bool $hide = false;

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:stories,title,'.$this->story->id],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'hide' => ['boolean'],
        ];
    }

    #[On('edit-story')]
    public function openModal(string $storyId): void
    {
        $this->story = Story::query()
            ->with('images')
            ->find($storyId);

        if ($this->story) {
            $this->title = $this->story->title;
            $this->subtitle = $this->story->subtitle ?? '';
            $this->description = $this->story->description ?? '';
            $this->hide = $this->story->hide;

            Flux::modal('edit-story')->show();
        }
    }

    public function closeModal(): void
    {
        Flux::modal('edit-story')->close();

        $this->dispatch('story-updated', storyId: $this->story->id);
        $this->resetForm();
    }

    public function updateStory(): void
    {
        $this->validate();

        $this->story->update([
            'title' => $this->title,
            'subtitle' => $this->subtitle ?: null,
            'description' => $this->description ?: null,
            'hide' => $this->hide,
        ]);

        Flux::modal('edit-story')->close();
        Flux::toast(text: 'Story updated successfully.', variant: 'success');

        $this->dispatch('story-updated', storyId: $this->story->id);
        $this->resetForm();
    }

    public function deleteStory(): void
    {
        $this->story->delete();

        Flux::modal('delete-story')->close();
        Flux::toast(text: 'Story deleted successfully.', variant: 'success');

        $this->dispatch('story-deleted', storyId: $this->story->id);
        $this->resetForm();
    }

    public function confirmStoryDelete(): void
    {
        Flux::modal('edit-story')->close();
        Flux::modal('delete-story')->show();
    }

    public function cancelStoryDelete(): void
    {
        Flux::modal('delete-story')->close();
        Flux::modal('edit-story')->show();
    }

    private function resetForm(): void
    {
        $this->story = null;
        $this->reset(['title', 'subtitle', 'description', 'hide']);
        $this->resetValidation();
    }
};
?>

<div>
    <flux:modal name="edit-story" class="md:w-xl" :dismissible="false" :closable="false">
        <section class="space-y-5 text-left">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <flux:heading size="lg">Edit Story</flux:heading>
                    <flux:text class="mt-1">Update story details and visibility status.</flux:text>
                </div>

                <flux:button variant="danger" type="button" wire:click="confirmStoryDelete">
                    Delete Story
                </flux:button>
            </div>

        @if($this->story)
                <div>
                    <label for="edit-story-title-{{ $this->story->id }}"
                           class="mb-2 block text-sm font-medium text-zinc-700">Title</label>
                    <input
                        id="edit-story-title-{{ $this->story->id }}"
                        type="text"
                        wire:model="title"
                        class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-zinc-500"
                        required
                    />
                    @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="edit-story-subtitle-{{ $this->story->id }}"
                           class="mb-2 block text-sm font-medium text-zinc-700">Subtitle</label>
                    <input
                        id="edit-story-subtitle-{{ $this->story->id }}"
                        type="text"
                        wire:model="subtitle"
                        class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-zinc-500"
                    />
                    @error('subtitle')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="edit-story-description-{{ $this->story->id }}"
                           class="mb-2 block text-sm font-medium text-zinc-700">Description</label>
                    <textarea
                        id="edit-story-description-{{ $this->story->id }}"
                        wire:model="description"
                        rows="4"
                        class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-zinc-500"
                    ></textarea>
                    @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2">
                    <input
                        id="edit-story-hide-{{ $this->story->id }}"
                        type="checkbox"
                        wire:model="hide"
                        class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-500"
                    />
                    <label for="edit-story-hide-{{ $this->story->id }}" class="text-sm text-zinc-700">Mark as
                        hidden</label>
                </div>
            @endif

            <div class="flex items-center justify-end gap-3 pt-2">
                <flux:button variant="ghost" type="button" wire:click="closeModal">
                    Cancel
                </flux:button>

                <flux:button variant="primary" type="button" wire:click="updateStory">
                    Save Changes
                </flux:button>
            </div>
        </section>
    </flux:modal>

    <flux:modal name="delete-story" class="max-w-md" :dismissible="false" :closable="false">
        <div class="space-y-2 text-left">
            <flux:heading size="lg">Delete story?</flux:heading>
            <flux:text>This action cannot be undone.</flux:text>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <flux:button variant="ghost" type="button" wire:click="cancelStoryDelete">
                Cancel
            </flux:button>

            <flux:button variant="danger" type="button" wire:click="deleteStory">
                Confirm Delete
            </flux:button>
        </div>
    </flux:modal>
</div>
