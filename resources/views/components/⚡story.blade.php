<?php

use App\Models\Story;
use Livewire\Component;

new class extends Component
{
    public Story $story;

    public function mount(): void
    {
        $this->story->loadMissing('images');
    }
};
?>

<div>
    <div class="mt-4 flex justify-center">
        <span class="text-2xl font-bold">
            {{ $story->title }}
        </span>
    </div>

    @if (filled($story->subtitle))
        <div class="mt-2 flex justify-center">
            <span class="text-sm text-gray-600">
                {{ $story->subtitle }}
            </span>
        </div>
    @endif

    @if (filled($story->description))
        <div class="my-4 flex justify-center">
            <span class="text-sm font-thin">
                {{ $story->description }}
            </span>
        </div>
    @endif

    @if ($story->images->isNotEmpty())
        <div class="my-2 grid grid-flow-row grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($story->images as $image)
                <div class="min-w-0">
                    <livewire:image :image="$image" :key="'story-image-'.$image->id"/>
                </div>
            @endforeach
        </div>
    @endif
</div>
