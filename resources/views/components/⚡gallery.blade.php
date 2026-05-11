<?php

use Livewire\Component;
use Illuminate\Support\Collection;
use App\Models\Image;
use App\Models\Story;

new class extends Component {
    public Collection $images;

    public Collection $stories;

    public function mount(): void
    {
        $this->images = Image::query()->where('hide', false)->get();

        $this->stories = Story::query()->where('hide', false)->get();
    }
};

?>

<div>
    <div>
        @foreach ($stories as $story)
            <div wire:key="{{ $story->id }}">
                Story {{ $story->id }}: {{ $story->title }}
            </div>
        @endforeach
    </div>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($images as $image)
            <div class="min-w-0">
                <livewire:image :image="$image" :key="'gallery-image-'.$image->id" />
            </div>
        @endforeach
    </div>
</div>
