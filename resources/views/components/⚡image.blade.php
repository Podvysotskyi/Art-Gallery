<?php

use App\Models\Image;
use Livewire\Component;

new class extends Component
{
    public Image $image;

    public function preview(): void
    {
        $this->dispatch('preview', id: $this->image->id);
    }
};
?>

<div
    class="relative block cursor-pointer overflow-hidden rounded-md bg-gray-200 bg-cover bg-center hover:shadow-lg/30"
    style="background-image: url('{{ asset('storage/images/previews/'.$image->id.'.jpg') }}')"
    wire:click="preview"
>
    <img
        class="aspect-square w-full object-cover"
        alt="{{ $image->title ?? $image->id.' image' }}"
        src="{{ asset('storage/images/'.$image->id.'.jpg') }}"
        loading="lazy"
    />

    @if (filled($image->title))
        <div class="absolute bottom-0 left-0 flex h-full w-full items-center justify-center bg-black/40 opacity-0 hover:opacity-100">
            <span class="font-bold text-white uppercase">
                {{ $image->title }}
            </span>
        </div>
    @endif
</div>
