<?php

namespace App\Actions\Image;

use App\Models\Image;
use Exception;
use Illuminate\Http\UploadedFile;
use Spatie\Image\Exceptions\CouldNotLoadImage;

class CreateImageAction extends ImageAction
{
    public function __construct(private readonly DeleteImageAction $deleteImageAction) {}

    public function handle(UploadedFile $uploadedFile, string $title, bool $hide): ?Image
    {
        $hash = self::getFileHash($uploadedFile);
        $filename = self::getFileName($uploadedFile);

        $image = Image::query()->where('hash', $hash)->first();

        if ($image) {
            return $this->updateImage($image, $title ?: $filename, $hide);
        }

        try {
            $image = $this->createImage($uploadedFile, $hash, $title ?: $filename, $hide);
        } catch (Exception) {
            if ($image !== null) {
                $this->deleteImageAction->handle($image);
            }

            return null;
        }

        return $image;
    }

    private static function getFileHash(UploadedFile $uploadedFile): string
    {
        return hash_file('sha256', $uploadedFile->getRealPath());
    }

    private static function getFileName(UploadedFile $uploadedFile): string
    {
        return pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
    }

    private function updateImage(Image $image, string $title, bool $hide): Image
    {
        $image->fill([
            'title' => $title ?: $image->title,
            'hide' => $hide,
        ])->save();

        return $image;
    }

    /**
     * @throws CouldNotLoadImage
     */
    private function createImage(UploadedFile $uploadedFile, string $hash, string $title, bool $hide): ?Image
    {
        $image = Image::create([
            'title' => $title,
            'hash' => $hash,
            'hide' => $hide,
        ]);

        $content = self::saveImageToLocalStorage($uploadedFile, "{$image->id}.jpg", 'uploads');
        self::saveImageToPublicStorage($content, "{$image->id}.jpg", 'images');

        $content = self::createImagePreview("{$image->id}.jpg");
        self::saveImageToPublicStorage($content, "{$image->id}.jpg", 'images/previews');

        self::deleteImageFromLocalStorage("{$image->id}.jpg", 'uploads');

        return $image;
    }
}
