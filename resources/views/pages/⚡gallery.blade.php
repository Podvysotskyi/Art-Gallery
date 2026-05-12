<?php

use App\Models\Story;
use App\Actions\GetAllImages;
use App\Actions\GetAllStories;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Welcome')] class extends Component
{
    public Collection $images;

    public Collection $stories;

    public ?Story $selectedStory = null;

    public function mount(GetAllStories $getAllStories, GetAllImages $getAllImages,): void
    {
        $this->images = $getAllImages();
        $this->stories = $getAllStories();
    }

    public function selectStory(?string $storyId = null): void
    {
        $this->selectedStory = $this->stories->firstWhere('id', $storyId);
    }
};
?>

<div>
    <div class="mb-2 flex justify-center gap-4">
        <button type="button" class="{{ empty($selectedStory) ? 'text-gray-800' : 'text-gray-500' }} hover:text-black cursor-pointer"
            wire:click="selectStory">
            All
        </button>

        @foreach ($stories as $story)
            <button type="button"
                class="{{ $selectedStory?->id === $story->id ? 'text-gray-800' : 'text-gray-500' }} hover:text-black cursor-pointer"
                wire:key="gallery-story-{{ $story->id }}" wire:click="selectStory('{{ $story->id }}')">
                {{ $story->title }}
            </button>
        @endforeach
    </div>

    @if (!empty($selectedStory?->subtitle) || !empty($selectedStory?->description))
        <div class="flex flex-col justify-center">
            @if (!empty($selectedStory->subtitle))
                <div class="text-center mb-2 text-sm text-gray-400">
                    {{ $selectedStory->subtitle }}
                </div>
            @endif
            @if (!empty($selectedStory->description))
                <div class="text-center mb-2 text-base text-gray-500">
                    {{ $selectedStory->description }}
                </div>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 mt-4">
        @foreach ($this->selectedStory->images ?? $images as $image)
            <div class="min-w-0" wire:key="gallery-image-wrapper-{{ $image->id }}">
                <livewire:image :image="$image" :key="'gallery-image-'.$image->id" />
            </div>
        @endforeach
    </div>
</div>
