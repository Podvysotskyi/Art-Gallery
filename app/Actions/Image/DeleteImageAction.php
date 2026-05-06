<?php

namespace App\Actions\Image;

use App\Models\Image;

class DeleteImageAction extends ImageAction
{
    public function handle(Image $image): void
    {
        $image->delete();

        self::deleteImageFromLocalStorage("{$image->id}.jpg", 'uploads');
        self::deleteImageFromPublicStorage("{$image->id}.jpg", 'images');
        self::deleteImageFromPublicStorage("{$image->id}.jpg", 'images/previews');
    }
}
