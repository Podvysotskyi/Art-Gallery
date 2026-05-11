<?php

namespace App\Actions;

use App\Models\Image;
use Illuminate\Support\Collection;

class GetAllImages
{
    public function __invoke(): Collection
    {
        return Image::query()
            ->where('hide', false)
            ->get();
    }
}
