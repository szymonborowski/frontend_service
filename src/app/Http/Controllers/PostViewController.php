<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsEventPublisher;
use App\Services\BlogApiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostViewController extends Controller
{
    public function __construct(
        protected BlogApiService $blogApi,
        protected AnalyticsEventPublisher $analyticsPublisher,
    ) {}

    public function show(string $slugOrId, Request $request): View
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

        $postUuid = $post['uuid'] ?? null;
        if ($postUuid) {
            $userId = session('user_id');
            $this->analyticsPublisher->publishPostViewed($postUuid, $userId, $request);
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
