<?php

namespace App\Actions\Image;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Enums\Fit as SpatieFit;
use Spatie\Image\Exceptions\CouldNotLoadImage;
use Spatie\Image\Image as SpatieImage;

abstract class ImageAction
{
    protected static function saveImageToLocalStorage(UploadedFile $uploadedFile, string $fileName, string $path): string
    {
        if (! Storage::disk('local')->exists($path)) {
            Storage::disk('local')->makeDirectory($path);
        }

        Storage::disk('local')->putFileAs($path, $uploadedFile, $fileName);

        return Storage::disk('local')->get("{$path}/{$fileName}");
    }

    protected static function saveImageToPublicStorage(string $content, string $fileName, string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        Storage::disk('public')->put("{$path}/{$fileName}", $content);
    }

    protected static function deleteImageFromLocalStorage(string $fileName, string $path): void
    {
        if (Storage::disk('local')->exists("{$path}/{$fileName}")) {
            Storage::disk('local')->delete("{$path}/{$fileName}");
        }
    }

    protected static function deleteImageFromPublicStorage(string $fileName, string $path): void
    {
        if (Storage::disk('public')->exists("{$path}/{$fileName}")) {
            Storage::disk('public')->delete("{$path}/{$fileName}");
        }
    }

    /**
     * @throws CouldNotLoadImage
     */
    protected static function createImagePreview(string $fileName): string
    {
        $imagePath = Storage::disk('local')->path("uploads/{$fileName}");

        SpatieImage::load($imagePath)
            ->fit(SpatieFit::Crop, 240, 240)
            ->save();

        return Storage::disk('local')->get("uploads/{$fileName}");
    }
}
