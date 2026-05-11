<?php

namespace App\Actions\Image;

use App\Models\Image;

class DeleteImageAction extends ImageAction
{
    public function __invoke(Image $image): void
    {
        $image->delete();

        self::deleteImageFromLocalStorage("{$image->id}.jpg", 'uploads');
        self::deleteImageFromPublicStorage("{$image->id}.jpg", 'images');
        self::deleteImageFromPublicStorage("{$image->id}.jpg", 'images/previews');
    }
}
