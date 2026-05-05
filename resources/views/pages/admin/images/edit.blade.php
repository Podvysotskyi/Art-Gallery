<?php

use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts::admin')]
#[Title('Edit Image')]
class extends Component
{
    public Image $image;

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

    public function mount(): void
    {
        $this->title = (string) ($this->image->title ?? '');
        $this->hide = (bool) $this->image->hide;
    }

    public function updateImage()
    {
        $this->validate();

        $this->image->update([
            'title' => $this->title !== '' ? $this->title : null,
            'hide' => $this->hide,
        ]);

        Flux::toast(text: 'Image updated.', variant: 'success');

        return $this->redirect('/admin/images', navigate: true);
    }

    public function deleteImage()
    {
        $this->image->delete();

        Storage::disk('public')->delete("images/{$this->image->id}.jpg");
        Storage::disk('public')->delete("images/previews/{$this->image->id}.jpg");

        Flux::toast(text: 'Image deleted successfully.', variant: 'success');

        return $this->redirect('/admin/images', navigate: true);
    }
};
?>

<div>
    <section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
        <div class="mb-6 flex items-start justify-between gap-4">
            <div>
                <h2 class="text-base font-semibold text-zinc-900">Edit Image</h2>
                <p class="mt-1 text-sm text-zinc-600">
                    Update image title and visibility status.
                </p>
            </div>

            <flux:modal.trigger name="delete-image-{{ $this->image->id }}">
                <flux:button variant="danger">
                    Delete Image
                </flux:button>
            </flux:modal.trigger>
        </div>

        <form class="space-y-5">
            <div class="grid gap-5 lg:grid-cols-[96px_minmax(0,1fr)] lg:items-start">
                <img
                    src="{{ asset('storage/images/'.$this->image->id.'.jpg') }}"
                    alt="{{ $this->image->title ?: 'Image preview' }}"
                    class="h-24 w-24 rounded-md bg-zinc-100 object-cover"
                    loading="lazy"
                />

                <div class="space-y-5">
                    <div>
                        <label for="title" class="mb-2 block text-sm font-medium text-zinc-700">Image Name</label>
                        <input
                            id="title"
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
                            id="hide"
                            type="checkbox"
                            wire:model="hide"
                            class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-500"
                        />
                        <label for="hide" class="text-sm text-zinc-700">Mark as hidden</label>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3 pt-2">
                <a
                    href="{{ route('admin.images') }}"
                    class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:border-zinc-400 hover:text-zinc-900"
                    wire:navigate
                >
                    Back
                </a>

                <flux:button variant="primary" type="button" wire:click="updateImage">
                    Save Changes
                </flux:button>
            </div>
        </form>
    </section>

    <flux:modal name="delete-image-{{ $this->image->id }}" class="max-w-md">
        <div class="space-y-2">
            <flux:heading size="lg">Delete image?</flux:heading>
            <flux:text>This action cannot be undone.</flux:text>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <flux:button variant="ghost" type="button" onclick="window.Flux.modals().close()">
                Cancel
            </flux:button>

            <flux:button variant="danger" type="button" wire:click="deleteImage">
                Confirm Delete
            </flux:button>
        </div>
    </flux:modal>
</div>
