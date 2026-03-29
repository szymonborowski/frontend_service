<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsApiService;
use App\Services\AnalyticsEventPublisher;
use App\Services\BlogApiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostViewController extends Controller
{
    public function __construct(
        protected BlogApiService $blogApi,
        protected AnalyticsEventPublisher $analyticsPublisher,
        protected AnalyticsApiService $analyticsApi,
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
            $userId = session('user.id');
            $this->analyticsPublisher->publishPostViewed($postUuid, $userId, $request);
        }

        $recentPosts = $this->blogApi->getRecentPosts(5);
        $categories = $this->blogApi->getCategories();
        $tags = $this->blogApi->getTags();
        $commentsPage = max(1, (int) $request->query('comments_page', 1));
        $commentsResult = $this->blogApi->getPostComments($post['id'], $commentsPage);

        // Fetch like data for post and comments
        $comments = $commentsResult['data'] ?? [];
        $likesItems = [];

        if ($postUuid) {
            $likesItems[] = ['type' => 'post', 'id' => $postUuid];
        }

        foreach ($comments as $comment) {
            if (isset($comment['id'])) {
                $likesItems[] = ['type' => 'comment', 'id' => (string) $comment['id']];
            }
        }

        $likesData = [];
        if (!empty($likesItems)) {
            $batchResult = $this->analyticsApi->getBatchLikes($likesItems, $request->ip());
            foreach ($batchResult as $item) {
                $likesData[$item['type'] . ':' . $item['id']] = $item;
            }
        }

        $userRoles = session('user.roles', []);
        $canComment = session('access_token') &&
            !empty($userRoles) &&
            count(array_filter($userRoles, fn($r) => $r !== 'guest')) > 0;

        return view('post', [
            'post' => $post,
            'recentPosts' => $recentPosts,
            'categories' => $categories,
            'tags' => $tags,
            'comments' => $comments,
            'commentsMeta' => $commentsResult['meta'] ?? [],
            'likesData' => $likesData,
            'canComment' => $canComment,
        ]);
    }
}
