<?php

namespace App\Actions;

use App\Models\Story;
use Illuminate\Support\Collection;

class GetAllStories
{
    public function __invoke(): Collection
    {
        return Story::query()
            ->where('hide', false)
            ->with(['images' => fn ($query) => $query->where('hide', false)])
            ->get();
    }
}
