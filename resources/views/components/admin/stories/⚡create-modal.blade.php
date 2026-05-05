<?php

use App\Models\Story;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    #[Validate]
    public string $title = '';

    #[Validate]
    public string $subtitle = '';

    #[Validate]
    public string $description = '';

    #[Validate]
    public bool $hide = true;

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:stories,title'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'hide' => ['boolean'],
        ];
    }

    public function createStory(): void
    {
        $this->validate();

        $story = Story::query()->create([
            'title' => $this->title,
            'subtitle' => $this->subtitle ?: null,
            'description' => $this->description ?: null,
            'hide' => $this->hide,
        ]);

        $this->dispatch('story-updated', storyId: $story->id);

        Flux::toast(text: 'Story created successfully.', variant: 'success');
        Flux::modal('create-story')->close();
    }

    public function resetForm(): void
    {
        $this->reset(['title', 'subtitle', 'description', 'hide']);

        $this->resetValidation();
    }
};
?>

<div>
    <flux:modal.trigger name="create-story">
        <flux:button variant="primary">Create Story</flux:button>
    </flux:modal.trigger>

    <flux:modal name="create-story" class="md:w-xl" :dismissible="false" wire:cancel="resetForm" wire:close="resetForm">
        <section class="space-y-5 text-left">
            <div>
                <flux:heading size="lg">New Story</flux:heading>
                <flux:text class="mt-1">Create a new story for the gallery.</flux:text>
            </div>

            <div>
                <label for="create-story-title" class="mb-2 block text-sm font-medium text-zinc-700">Title</label>
                <input
                    id="create-story-title"
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
                <label for="create-story-subtitle" class="mb-2 block text-sm font-medium text-zinc-700">Subtitle</label>
                <input
                    id="create-story-subtitle"
                    type="text"
                    wire:model="subtitle"
                    class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-zinc-500"
                />
                @error('subtitle')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="create-story-description"
                       class="mb-2 block text-sm font-medium text-zinc-700">Description</label>
                <textarea
                    id="create-story-description"
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
                    id="create-story-hide"
                    type="checkbox"
                    wire:model="hide"
                    class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-500"
                />
                <label for="create-story-hide" class="text-sm text-zinc-700">Mark as hidden</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <flux:button variant="primary" type="button" wire:click="createStory">
                    Create Story
                </flux:button>
            </div>
        </section>
    </flux:modal>
</div>
