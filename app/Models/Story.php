<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable(['title', 'subtitle', 'hide', 'description'])]
class Story extends Model
{
    use HasUuids;

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'hide' => false,
        'description' => null,
    ];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'entity');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hide' => 'boolean',
        ];
    }
}
