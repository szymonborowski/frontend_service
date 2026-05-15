<?php

namespace App\Http\Controllers;

use App\Services\BlogApiService;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function __construct(
        protected BlogApiService $blogApi,
    ) {}

    public function index(): View
    {
        $featuredPosts = [];
        try {
            $featuredPosts = array_slice($this->blogApi->getMostImportantPosts(), 0, 3);
        } catch (\Throwable) {
            // blog API unreachable — show homepage without posts
        }

        return view('homepage', compact('featuredPosts'));
    }
}
