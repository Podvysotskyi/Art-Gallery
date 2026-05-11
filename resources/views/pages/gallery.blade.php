<x-layouts::app :title="__('Welcome')">
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
</x-layouts::app>
