<?php

use App\Models\Image;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public Collection $images;

    public function mount(): void
    {
        $this->refreshList();
    }

    #[On('image-updated')]
    public function refreshList(): void
    {
        $this->images = Image::query()
            ->with('entity')
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
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Preview</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Title</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Type</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Status</th>
            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-600">Uploaded</th>
            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-600">Action</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 bg-white">
        <tr>
            <td colspan="6" class="px-4 py-10 text-center text-sm text-zinc-500">Loading images...</td>
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
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Preview</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Title</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Type</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600">Status</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-600">Uploaded
                </th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-600">Action</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white">
            @forelse($this->images as $image)
                <tr>
                    <td class="px-4 py-3">
                        <img
                            src="{{ asset('storage/images/previews/'.$image->id.'.jpg') }}"
                            alt="{{ $image->title ?: 'Image preview' }}"
                            class="h-12 w-12 rounded-md bg-zinc-100 object-cover"
                            loading="lazy"
                        />
                    </td>
                    <td class="px-4 py-3 text-sm text-zinc-800">{{ $image->title ?? '' }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-600">
                        {{ class_basename($image->entity_type ?? 'Image') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-zinc-600">{{ $image->hide ? 'Hidden' : 'Published' }}</td>
                    <td class="px-4 py-3 text-right text-sm text-zinc-600">{{ $image->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <button
                            type="button"
                            wire:click="$dispatch('edit-image', {imageId: '{{ $image->id }}'})"
                            class="rounded-md border border-zinc-300 px-3 py-1.5 text-sm font-medium text-zinc-700 transition hover:border-zinc-400 hover:text-zinc-900"
                        >
                            Edit
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-sm text-zinc-500">
                        No images have been uploaded yet.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <livewire:admin.images.edit-modal/>
</div>
