<?php

namespace App\Http\Controllers;

use App\Services\BlogApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryViewController extends Controller
{
    public function __construct(
        protected BlogApiService $blogApi
    ) {}

    public function show(string $slug): View|RedirectResponse
    {
        $category = $this->blogApi->getCategoryBySlug($slug);

        if (!$category) {
            abort(404);
        }

        $postsResult = $this->blogApi->getPostsByCategoryId((int) $category['id'], 1, 15);
        $posts = $postsResult['data'] ?? [];
        $meta = $postsResult['meta'] ?? [];

        $recentPosts = $this->blogApi->getRecentPosts(5);
        $categories = $this->blogApi->getCategories();
        $tags = $this->blogApi->getTags();

        return view('category', [
            'category' => $category,
            'posts' => $posts,
            'meta' => $meta,
            'recentPosts' => $recentPosts,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }
}
