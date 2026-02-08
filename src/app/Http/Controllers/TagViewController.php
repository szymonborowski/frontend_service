<?php

namespace App\Http\Controllers;

use App\Services\BlogApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TagViewController extends Controller
{
    public function __construct(
        protected BlogApiService $blogApi
    ) {}

    public function show(string $slug): View|RedirectResponse
    {
        $tag = $this->blogApi->getTagBySlug($slug);

        if (!$tag) {
            abort(404);
        }

        $postsResult = $this->blogApi->getPostsByTagId((int) $tag['id'], 1, 15);
        $posts = $postsResult['data'] ?? [];
        $meta = $postsResult['meta'] ?? [];

        $recentPosts = $this->blogApi->getRecentPosts(5);
        $categories = $this->blogApi->getCategories();
        $tags = $this->blogApi->getTags();

        return view('tag', [
            'tag' => $tag,
            'posts' => $posts,
            'meta' => $meta,
            'recentPosts' => $recentPosts,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }
}
