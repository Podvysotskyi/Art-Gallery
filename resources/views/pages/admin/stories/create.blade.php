<?php

use App\Models\Story;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts::admin')]
#[Title('Create Story')]
class extends Component
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

    public function createStory()
    {
        $this->validate();

        Story::create([
            'title' => $this->title,
            'subtitle' => $this->subtitle ?: null,
            'description' => $this->description ?: null,
            'hide' => $this->hide,
        ]);

        Flux::toast(text: 'Story created successfully.', variant: 'success');

        return $this->redirect('/admin/stories', navigate: true);
    }
};
?>

<section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
    <div class="mb-6">
        <h2 class="text-base font-semibold text-zinc-900">New Story</h2>
        <p class="mt-1 text-sm text-zinc-600">
            Create a new story for the gallery.
        </p>
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
            <label for="description" class="mb-2 block text-sm font-medium text-zinc-700">Description</label>
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

        <div class="flex items-center gap-3 pt-2">
            <flux:button variant="primary" type="button" wire:click="createStory">
                Create Story
            </flux:button>
            <a
                href="{{ route('admin.stories') }}"
                class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:border-zinc-400 hover:text-zinc-900"
                wire:navigate
            >
                Cancel
            </a>
        </div>
    </form>
</section>
