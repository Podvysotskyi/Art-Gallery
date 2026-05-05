<?php

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image as SpatieImage;

new #[Layout('layouts::admin')]
#[Title('Create Image')]
class extends Component
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

    public function createImage()
    {
        $this->validate();

        $defaultTitle = pathinfo($this->image->getClientOriginalName(), PATHINFO_FILENAME);

        $image = Image::make([
            'title' => $this->title ?: $defaultTitle,
            'hash' => hash_file('sha256', $this->image->getRealPath()),
            'hide' => $this->hide,
        ]);

        try {
            $image->save();
        } catch (Exception) {
            Flux::toast(text: 'Image already exists.', variant: 'warning');

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

            Flux::toast(text: 'Failed to upload the image.', variant: 'error');

            return;
        }

        Flux::toast(text: 'Image created successfully.', variant: 'success');

        return $this->redirect('/admin/images', navigate: true);
    }
};
?>

<section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
    <div class="mb-6">
        <h2 class="text-base font-semibold text-zinc-900">New Image</h2>
        <p class="mt-1 text-sm text-zinc-600">
            Add a new image record to the gallery.
        </p>
    </div>

    <form class="space-y-5">
        <div>
            <label for="title" class="mb-2 block text-sm font-medium text-zinc-700">Title</label>
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

        <flux:input
            id="image"
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
                id="hide"
                type="checkbox"
                wire:model="hide"
                class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-500"
            />
            <label for="hide" class="text-sm text-zinc-700">Mark as hidden</label>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <flux:button variant="primary" type="button" wire:click="createImage">
                Create Image
            </flux:button>
            <a
                href="{{ route('admin.images') }}"
                class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:border-zinc-400 hover:text-zinc-900"
                wire:navigate
            >
                Cancel
            </a>
        </div>
    </form>
</section>
