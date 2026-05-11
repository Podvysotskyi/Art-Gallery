<?php

use App\Actions\Image\CreateImageAction;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public bool $showTrigger = true;

    #[Validate]
    public string $title = '';

    #[Validate]
    public ?UploadedFile $image = null;

    #[Validate]
    public bool $hide = true;

    protected function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'file', 'mimes:jpg,jpeg', 'max:10240'],
            'hide' => ['boolean'],
        ];
    }

    #[On('create-image')]
    public function openModal(): void
    {
        Flux::modal('create-image')->show();
    }

    public function closeModal(): void
    {
        Flux::modal('create-image')->close();

        $this->dispatch('image-created', imageId: null);
        $this->resetForm();
    }

    public function createImage(CreateImageAction $action): void
    {
        $this->validate();

        $image = $action($this->image, $this->title, $this->hide);

        if ($image) {
            Flux::toast(text: 'Image created successfully.', variant: 'success');
        } else {
            Flux::toast(text: 'Failed to create the image.', variant: 'danger');
        }

        Flux::modal('create-image')->close();

        $this->dispatch('image-created', imageId: $image?->id);
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['title', 'image', 'hide']);
        $this->resetValidation();
    }
};
?>

<div>
    @if ($showTrigger)
        <flux:modal.trigger name="create-image">
            <flux:button variant="primary" type="button">
                Upload Image
            </flux:button>
        </flux:modal.trigger>
    @endif

    <flux:modal name="create-image" class="md:w-xl" :dismissible="false" :closable="false">
        <section class="space-y-5 text-left">
            <div>
                <flux:heading size="lg">New Image</flux:heading>
                <flux:text class="mt-1">Add a new image record to the gallery.</flux:text>
            </div>

            <div>
                <label for="create-title" class="mb-2 block text-sm font-medium text-zinc-700">Title</label>
                <input
                    id="create-title"
                    type="text"
                    wire:model="title"
                    class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-zinc-500"
                />
                @error('title')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <flux:input
                id="create-image"
                type="file"
                wire:model="image"
                :label="__('Image File (JPG)')"
                accept=".jpg,.jpeg,image/jpeg"
                required
            />
            @error('image')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror

            <div class="flex items-center gap-2">
                <input
                    id="create-hide"
                    type="checkbox"
                    wire:model="hide"
                    class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-500"
                />
                <label for="create-hide" class="text-sm text-zinc-700">Mark as hidden</label>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <flux:button variant="ghost" type="button" wire:click="closeModal">
                    Cancel
                </flux:button>

                <flux:button variant="primary" type="button" wire:click="createImage">
                    Create Image
                </flux:button>
            </div>
        </section>
    </flux:modal>
</div>
