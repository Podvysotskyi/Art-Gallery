<?php

use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    #[Validate]
    public string $title = '';

    #[Validate]
    public bool $hide = false;

    protected function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'hide' => ['boolean'],
        ];
    }

    public ?Image $image = null;

    #[On('edit-image')]
    public function openEditImage(string $imageId): void
    {
        $this->image = Image::query()->find($imageId);

        if ($this->image) {
            $this->title = $this->image->title ?? '';
            $this->hide = $this->image->hide;

            Flux::modal('edit-image')->show();
        }
    }

    public function updateImage(): void
    {
        $this->validate();

        $this->image->update([
            'title' => $this->title !== '' ? $this->title : null,
            'hide' => $this->hide,
        ]);

        $this->dispatch('image-updated', imageId: $this->image->id);

        Flux::toast(text: 'Image updated.', variant: 'success');
        Flux::modal('edit-image')->close();
    }

    public function deleteImage(): void
    {
        $this->image->delete();

        if (Storage::disk('public')->exists("images/{$this->image->id}.jpg")) {
            Storage::disk('public')->delete("images/{$this->image->id}.jpg");
        }
        if (Storage::disk('public')->exists("images/previews/{$this->image->id}.jpg")) {
            Storage::disk('public')->delete("images/previews/{$this->image->id}.jpg");
        }

        $this->dispatch('image-updated', imageId: $this->image->id);

        Flux::toast(text: 'Image deleted successfully.', variant: 'success');
        Flux::modal('delete-image')->close();
        Flux::modal('edit-image')->close();
    }

    public function resetForm(): void
    {
        $this->image = null;
        $this->reset(['title', 'hide']);

        $this->resetValidation();
    }
};
?>

<div>
    <flux:modal name="edit-image" class="md:w-xl" :dismissible="false" wire:cancel="resetForm" wire:close="resetForm">
        <section class="space-y-6 text-left">
            <div>
                <div>
                    <flux:heading size="lg">Edit Image</flux:heading>
                    <flux:text class="mt-1">Update image title and visibility status.</flux:text>
                </div>
            </div>

            @if($this->image)
                <div class="grid gap-5 lg:grid-cols-[96px_minmax(0,1fr)] lg:items-start">
                    <img
                        src="{{ asset('storage/images/'.$this->image->id.'.jpg') }}"
                        alt="{{ $this->image->title ?: 'Image preview' }}"
                        class="h-24 w-24 rounded-md bg-zinc-100 object-cover"
                        loading="lazy"
                    />

                    <div class="space-y-5">
                        <div>
                            <label for="title-{{ $this->image->id }}"
                                   class="mb-2 block text-sm font-medium text-zinc-700">Image Name</label>
                            <input
                                id="title-{{ $this->image->id }}"
                                type="text"
                                wire:model="title"
                                class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-zinc-500"
                            />
                            @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-2">
                            <input
                                id="hide-{{ $this->image->id }}"
                                type="checkbox"
                                wire:model="hide"
                                class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-500"
                            />
                            <label for="hide-{{ $this->image->id }}" class="text-sm text-zinc-700">Mark as
                                hidden</label>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-wrap items-center justify-end gap-3 pt-2">
                <flux:modal.trigger name="delete-image">
                    <flux:button variant="danger" type="button">Delete Image</flux:button>
                </flux:modal.trigger>

                <flux:button variant="primary" type="button" wire:click="updateImage">
                    Save Changes
                </flux:button>
            </div>
        </section>
    </flux:modal>

    <flux:modal name="delete-image" class="max-w-md" :dismissible="false">
        <div class="space-y-2 text-left">
            <flux:heading size="lg">Delete image?</flux:heading>
            <flux:text>This action cannot be undone.</flux:text>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <flux:button variant="ghost" type="button" x-on:click="$flux.modal('delete-image').close()">
                Cancel
            </flux:button>

            <flux:button variant="danger" type="button" wire:click="deleteImage">
                Confirm Delete
            </flux:button>
        </div>
    </flux:modal>
</div>
