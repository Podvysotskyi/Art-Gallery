<?php

use App\Models\Story;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public Collection $stories;

    public function mount(): void
    {
        $this->refreshList();
    }

    #[On('story-created')]
    #[On('story-updated')]
    #[On('story-deleted')]
    public function refreshList(): void
    {
        $this->stories = Story::query()
            ->with('images')
            ->latest()
            ->get();
    }
};
?>

@placeholder
<div class="overflow-hidden rounded-md border border-zinc-200">
    <table class="min-w-full divide-y divide-zinc-200">
        <thead class="bg-zinc-50">
        <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Title</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Subtitle</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Status</th>
            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-600">Created</th>
            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-600">Action</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 bg-white">
        <tr>
            <td colspan="5" class="px-4 py-10 text-center text-sm text-zinc-500">Loading stories...</td>
        </tr>
        </tbody>
    </table>
</div>
@endplaceholder

<div>
    <div class="overflow-hidden rounded-md border border-zinc-200">
        <table class="min-w-full divide-y divide-zinc-200">
            <thead class="bg-zinc-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Title</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Subtitle
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Status</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-600">Created
                </th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-600">Action</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white">
            @forelse($this->stories as $story)
                <tr>
                    <td class="px-4 py-3 text-sm text-zinc-800">{{ $story->title }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-600">{{ $story->subtitle }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-600">{{ $story->hide ? 'Hidden' : 'Published' }}</td>
                    <td class="px-4 py-3 text-right text-sm text-zinc-600">{{ $story->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-2">
                            <button
                                type="button"
                                wire:click="$dispatch('open-story-images', { storyId: '{{ $story->id }}' })"
                                class="rounded-md border border-zinc-300 px-3 py-1.5 text-sm font-medium text-zinc-700 transition hover:border-zinc-400 hover:text-zinc-900"
                            >
                                Images
                            </button>
                            <button
                                type="button"
                                wire:click="$dispatch('edit-story', { storyId: '{{ $story->id }}' })"
                                class="rounded-md border border-zinc-300 px-3 py-1.5 text-sm font-medium text-zinc-700 transition hover:border-zinc-400 hover:text-zinc-900"
                            >
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-sm text-zinc-500">
                        No stories found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <livewire:admin.stories.images-modal/>
    <livewire:admin.stories.edit-modal/>
</div>
