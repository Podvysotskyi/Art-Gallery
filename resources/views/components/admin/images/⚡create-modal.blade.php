<?php

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image as SpatieImage;

new class extends Component
{
    use WithFileUploads;

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
            'image' => ['required', 'file', 'mimes:jpg,jpeg'],
            'hide' => ['boolean'],
        ];
    }

    public function createImage(): void
    {
        $this->validate();

        $filename = pathinfo($this->image->getClientOriginalName(), PATHINFO_FILENAME);

        $image = Image::make([
            'title' => $this->title ?: $filename,
            'hash' => hash_file('sha256', $this->image->getRealPath()),
            'hide' => $this->hide,
        ]);

        try {
            $image->save();
        } catch (Exception) {
            Flux::toast(text: 'Image already exists.', variant: 'danger');
            Flux::modal('create-image')->close();

            return;
        }

        try {
            $this->image->storeAs('images', "{$image->id}.jpg", 'public');

            $originalPath = Storage::disk('public')->path("images/{$image->id}.jpg");
            $previewPath = Storage::disk('public')->path("images/previews/{$image->id}.jpg");

            SpatieImage::load($originalPath)
                ->fit(Fit::Crop, 240, 240)
                ->save($previewPath);
        } catch (Exception) {
            $image->delete();

            if (Storage::disk('public')->exists("images/{$image->id}.jpg")) {
                Storage::disk('public')->delete("images/{$image->id}.jpg");
            }

            if (Storage::disk('public')->exists("images/previews/{$image->id}.jpg")) {
                Storage::disk('public')->delete("images/previews/{$image->id}.jpg");
            }

            Flux::toast(text: 'Failed to upload the image.', variant: 'danger');
            Flux::modal('create-image')->close();

            return;
        }

        Flux::toast(text: 'Image created successfully.', variant: 'success');
        Flux::modal('create-image')->close();

        $this->dispatch('image-updated');
    }

    public function resetForm(): void
    {
        $this->reset(['title', 'image', 'hide']);

        $this->resetValidation();
    }
};
?>

<div>
    <flux:modal.trigger name="create-image">
        <flux:button variant="primary">Upload Image</flux:button>
    </flux:modal.trigger>

    <flux:modal name="create-image" class="md:w-xl" :dismissible="false" wire:cancel="resetForm" wire:close="resetForm">
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
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
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

            <div class="flex items-center justify-end gap-3 pt-2">
                <flux:button variant="primary" type="button" wire:click="createImage">
                    Create Image
                </flux:button>
            </div>
        </section>
    </flux:modal>
</div>
