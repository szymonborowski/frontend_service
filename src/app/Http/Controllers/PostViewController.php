<?php

namespace App\Http\Controllers;

use App\Services\BlogApiService;
use Illuminate\View\View;

class PostViewController extends Controller
{
    public function __construct(
        protected BlogApiService $blogApi
    ) {}

    public function show(string $slugOrId): View
    {
        $post = null;

        if (is_numeric($slugOrId)) {
            $post = $this->blogApi->getPostById((int) $slugOrId);
        } else {
            $post = $this->blogApi->getPost($slugOrId);
        }

        if (!$post) {
            abort(404);
        }

        $recentPosts = $this->blogApi->getRecentPosts(5);
        $categories = $this->blogApi->getCategories();
        $tags = $this->blogApi->getTags();

        return view('post', [
            'post' => $post,
            'recentPosts' => $recentPosts,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }
}
