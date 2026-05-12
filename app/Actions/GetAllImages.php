<?php

namespace App\Actions;

use App\Models\Image;
use App\Models\Story;
use Illuminate\Database\Eloquent\Builder;
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
