<?php

namespace App\Http\Controllers;

use App\Services\BlogApiService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryViewController extends Controller
{
    private const ALLOWED_PER_PAGE = [10, 20, 30, 50];

    public function __construct(
        protected BlogApiService $blogApi
    ) {}

    public function show(Request $request, string $slug): View|RedirectResponse
    {
        $category = $this->blogApi->getCategoryBySlug($slug);

        if (!$category) {
            abort(404);
        }

        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, self::ALLOWED_PER_PAGE, true)) {
            $perPage = 10;
        }
        $page = max(1, (int) $request->get('page', 1));

        $postsResult = $this->blogApi->getPostsByCategoryId((int) $category['id'], $page, $perPage);
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
            'paginationRoute' => 'category.show',
            'paginationRouteParams' => ['slug' => $category['slug']],
            'currentPerPage' => $perPage,
            'allowedPerPage' => self::ALLOWED_PER_PAGE,
        ]);
    }
}
