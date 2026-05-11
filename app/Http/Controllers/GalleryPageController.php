<?php

namespace App\Http\Controllers;

use App\Actions\GetAllImages;
use App\Actions\GetAllStories;
use Illuminate\Contracts\View\View;

class GalleryPageController extends Controller
{
    public function __construct(
        private readonly GetAllImages $getAllImagesAction,
        private readonly GetAllStories $getAllStoriesAction
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $images = ($this->getAllImagesAction)();
        $stories = ($this->getAllStoriesAction)();

        return view('pages.gallery', [
            'images' => $images,
            'stories' => $stories,
        ]);
    }
}
