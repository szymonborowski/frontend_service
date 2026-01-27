<?php

namespace App\Http\Controllers;

use App\Services\BlogApiService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected BlogApiService $blogApi
    ) {}

    public function index(): View
    {
        $recentPosts = $this->blogApi->getRecentPosts(10);
        $featuredPost = $recentPosts[0] ?? null;
        $categories = $this->blogApi->getCategories();
        $tags = $this->blogApi->getTags();

        return view('home', [
            'recentPosts' => $recentPosts,
            'featuredPost' => $featuredPost,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }
}
