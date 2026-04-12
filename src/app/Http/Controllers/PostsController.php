<?php

namespace App\Http\Controllers;

use App\Services\BlogApiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostsController extends Controller
{
    private const ALLOWED_PER_PAGE = [10, 20, 30, 50];
    private const ALLOWED_SORT_BY  = ['published_at', 'title'];
    private const ALLOWED_SORT_ORDER = ['desc', 'asc'];

    public function __construct(
        protected BlogApiService $blogApi
    ) {}

    public function index(Request $request): View
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, self::ALLOWED_PER_PAGE, true)) {
            $perPage = 10;
        }

        $page = max(1, (int) $request->get('page', 1));

        $sortBy = $request->get('sort_by', 'published_at');
        if (!in_array($sortBy, self::ALLOWED_SORT_BY, true)) {
            $sortBy = 'published_at';
        }

        $sortOrder = $request->get('sort_order', 'desc');
        if (!in_array($sortOrder, self::ALLOWED_SORT_ORDER, true)) {
            $sortOrder = 'desc';
        }

        $search     = $request->filled('q') ? trim($request->get('q')) : null;
        $categoryId = $request->filled('category_id') ? (int) $request->get('category_id') : null;
        $tagId      = $request->filled('tag_id')      ? (int) $request->get('tag_id')      : null;

        $postsResult = $this->blogApi->getPosts(
            page: $page,
            perPage: $perPage,
            search: $search,
            categoryId: $categoryId,
            tagId: $tagId,
            sortBy: $sortBy,
            sortOrder: $sortOrder,
        );

        $posts = $postsResult['data'] ?? [];
        $meta  = $postsResult['meta'] ?? [];

        $categories  = $this->blogApi->getCategories();
        $tags        = $this->blogApi->getTags();
        $recentPosts = $this->blogApi->getRecentPosts(5);

        // Resolve active category/tag names for filter badges
        $activeCategory = null;
        if ($categoryId !== null) {
            foreach ($categories as $cat) {
                if ((int) $cat['id'] === $categoryId) {
                    $activeCategory = $cat;
                    break;
                }
            }
        }

        $activeTag = null;
        if ($tagId !== null) {
            foreach ($tags as $t) {
                if ((int) $t['id'] === $tagId) {
                    $activeTag = $t;
                    break;
                }
            }
        }

        // Extra params for pagination (preserve all active filters)
        $extraParams = array_filter([
            'q'           => $search,
            'category_id' => $categoryId,
            'tag_id'      => $tagId,
            'sort_by'     => $sortBy !== 'published_at' ? $sortBy : null,
            'sort_order'  => $sortOrder !== 'desc' ? $sortOrder : null,
        ]);

        return view('posts', [
            'posts'              => $posts,
            'meta'               => $meta,
            'recentPosts'        => $recentPosts,
            'categories'         => $categories,
            'tags'               => $tags,
            'search'             => $search,
            'categoryId'         => $categoryId,
            'tagId'              => $tagId,
            'sortBy'             => $sortBy,
            'sortOrder'          => $sortOrder,
            'activeCategory'     => $activeCategory,
            'activeTag'          => $activeTag,
            'paginationRoute'    => 'posts.index',
            'extraParams'        => $extraParams,
            'currentPerPage'     => $perPage,
            'allowedPerPage'     => self::ALLOWED_PER_PAGE,
        ]);
    }
}
