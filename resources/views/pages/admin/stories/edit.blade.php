<?php

use App\Models\Story;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts::admin')]
#[Title('Edit Story')]
class extends Component
{
    public Story $story;

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

    public function mount(): void
    {
        $this->title = $this->story->title;
        $this->subtitle = $this->story->subtitle ?? '';
        $this->description = $this->story->description ?? '';
        $this->hide = $this->story->hide;
    }

    public function updateStory()
    {
        $this->validate();

        $this->story->update([
            'title' => $this->title,
            'subtitle' => $this->subtitle ?: null,
            'description' => $this->description ?: null,
            'hide' => $this->hide,
        ]);

        Flux::toast(text: 'Story updated successfully.', variant: 'success');

        return $this->redirect('/admin/stories', navigate: true);
    }

    public function deleteStory()
    {
        $this->story->delete();

        Flux::toast(text: 'Story deleted successfully.', variant: 'success');

        return $this->redirect('/admin/stories', navigate: true);
    }
};
?>

<div>
    <section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        <flux:tab.group wire="tab">
            <flux:tabs>
                <flux:tab name="story">Story</flux:tab>
                <flux:tab name="images">Images</flux:tab>
            </flux:tabs>
            <flux:tab.panel name="story">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold text-zinc-900">Edit Story</h2>
                        <p class="mt-1 text-sm text-zinc-600">
                            Update story details and visibility status.
                        </p>
                    </div>

                    <flux:modal.trigger name="delete-story-{{ $this->story->id }}">
                        <flux:button variant="danger">
                            Delete Story
                        </flux:button>
                    </flux:modal.trigger>
                </div>

                <form class="space-y-5">
                    <div>
                        <label for="title" class="mb-2 block text-sm font-medium text-zinc-700">Title</label>
                        <input
                            id="title"
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
                        <label for="subtitle" class="mb-2 block text-sm font-medium text-zinc-700">Subtitle</label>
                        <input
                            id="subtitle"
                            type="text"
                            wire:model="subtitle"
                            class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-zinc-500"
                        />
                        @error('subtitle')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description"
                               class="mb-2 block text-sm font-medium text-zinc-700">Description</label>
                        <textarea
                            id="description"
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
                            id="hide"
                            type="checkbox"
                            wire:model="hide"
                            class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-500"
                        />
                        <label for="hide" class="text-sm text-zinc-700">Mark as hidden</label>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-3 pt-2">
                        <a
                            href="{{ route('admin.stories') }}"
                            class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:border-zinc-400 hover:text-zinc-900"
                            wire:navigate
                        >
                            Back
                        </a>

                        <flux:button variant="primary" type="button" wire:click="updateStory">
                            Save Changes
                        </flux:button>
                    </div>
                </form>
            </flux:tab.panel>
            <flux:tab.panel name="images">...</flux:tab.panel>
        </flux:tab.group>
    </section>
    <flux:modal name="delete-story-{{ $this->story->id }}" class="max-w-md">
        <div class="space-y-2">
            <flux:heading size="lg">Delete story?</flux:heading>
            <flux:text>This action cannot be undone.</flux:text>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <flux:button variant="ghost" type="button" onclick="window.Flux.modals().close()">
                Cancel
            </flux:button>

            <flux:button variant="danger" type="button" wire:click="deleteStory">
                Confirm Delete
            </flux:button>
        </div>
    </flux:modal>
</div>
