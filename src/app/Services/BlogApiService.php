<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BlogApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.blog.url') . '/api/v1';
    }

    protected function http(): PendingRequest
    {
        $token = session('access_token');

        $client = Http::withOptions([
            'verify' => false,  // Disable SSL verification for internal calls
            'allow_redirects' => false,  // Don't follow redirects
        ]);

        if ($token) {
            return $client->withToken($token);
        }

        return $client;
    }

    /**
     * Fetch all published posts for indexing (paginated, no cache).
     * Returns a flat array of posts with full content for both locales.
     */
    public function getAllPostsForIndexing(string $locale = 'en'): array
    {
        $posts   = [];
        $page    = 1;
        $perPage = 50;

        do {
            $response = $this->http()->get("{$this->baseUrl}/posts", [
                'status'   => 'published',
                'locale'   => $locale,
                'with'     => 'categories,tags,content',
                'page'     => $page,
                'per_page' => $perPage,
            ]);

            if (!$response->successful()) {
                break;
            }

            $data      = $response->json('data') ?? [];
            $lastPage  = $response->json('meta.last_page') ?? 1;

            foreach ($data as $post) {
                $post['locale'] = $locale;
                $posts[]        = $post;
            }

            $page++;
        } while ($page <= $lastPage);

        return $posts;
    }

    public function getRecentPosts(int $limit = 10): array
    {
        $locale = app()->getLocale();

        return Cache::remember("blog.posts.recent.{$limit}.{$locale}", 300, function () use ($limit, $locale) {
            $response = $this->http()->get("{$this->baseUrl}/posts", [
                'per_page' => $limit,
                'status' => 'published',
                'locale' => $locale,
                'with' => 'categories,tags,author',
            ]);

            if ($response->successful()) {
                return $response->json('data') ?? [];
            }

            return [];
        });
    }

    public function getMostImportantPosts(): array
    {
        $locale = app()->getLocale();

        return Cache::remember("blog.featured_posts.{$locale}", 300, function () use ($locale) {
            $response = $this->http()->get("{$this->baseUrl}/featured-posts", [
                'locale' => $locale,
            ]);

            if ($response->successful()) {
                return $response->json('data') ?? [];
            }

            return [];
        });
    }

    public function getCategories(): array
    {
        return Cache::remember('blog.categories', 300, function () {
            $response = $this->http()->get("{$this->baseUrl}/categories", [
                'with_count' => 'posts',
                'per_page' => 100,
            ]);

            if ($response->successful()) {
                $categories = $response->json('data') ?? [];
                usort($categories, fn($a, $b) => ($b['posts_count'] ?? 0) <=> ($a['posts_count'] ?? 0));
                return $categories;
            }

            return [];
        });
    }

    public function getTags(): array
    {
        return Cache::remember('blog.tags', 300, function () {
            $response = $this->http()->get("{$this->baseUrl}/tags");

            if ($response->successful()) {
                return $response->json('data') ?? [];
            }

            return [];
        });
    }

    public function getCategoryBySlug(string $slug): ?array
    {
        $categories = $this->getCategories();
        foreach ($categories as $category) {
            if (($category['slug'] ?? '') === $slug) {
                return $category;
            }
        }
        return null;
    }

    public function getTagBySlug(string $slug): ?array
    {
        $tags = $this->getTags();
        foreach ($tags as $tag) {
            if (($tag['slug'] ?? '') === $slug) {
                return $tag;
            }
        }
        return null;
    }

    public function getPostsByCategoryId(int $categoryId, int $page = 1, int $perPage = 15): array
    {
        $response = $this->http()->get("{$this->baseUrl}/posts", [
            'category_id' => $categoryId,
            'status' => 'published',
            'locale' => app()->getLocale(),
            'with' => 'categories,tags,author',
            'page' => $page,
            'per_page' => $perPage,
        ]);

        if ($response->successful()) {
            return $response->json() ?? ['data' => [], 'meta' => []];
        }
        return ['data' => [], 'meta' => []];
    }

    public function getPostsByTagId(int $tagId, int $page = 1, int $perPage = 15): array
    {
        $response = $this->http()->get("{$this->baseUrl}/posts", [
            'tag_id' => $tagId,
            'status' => 'published',
            'locale' => app()->getLocale(),
            'with' => 'categories,tags,author',
            'page' => $page,
            'per_page' => $perPage,
        ]);

        if ($response->successful()) {
            return $response->json() ?? ['data' => [], 'meta' => []];
        }
        return ['data' => [], 'meta' => []];
    }

    public function getPost(string $slug): ?array
    {
        $response = $this->http()->get("{$this->baseUrl}/posts", [
            'slug'   => $slug,
            'locale' => app()->getLocale(),
            'with'   => 'categories,tags',
        ]);

        if ($response->successful()) {
            $data = $response->json('data') ?? [];
            return $data[0] ?? null;
        }

        return null;
    }

    public function getPostById(int $id): ?array
    {
        $response = $this->http()->get("{$this->baseUrl}/posts/{$id}", [
            'with' => 'categories,tags,author',
        ]);

        if ($response->successful()) {
            return $response->json('data') ?? $response->json();
        }

        return null;
    }

    public function getUserPosts(int $userId, int $page = 1, int $perPage = 15): array
    {
        $response = $this->http()->get("{$this->baseUrl}/posts", [
            'author_id' => $userId,
            'with' => 'categories,tags,author',
            'page' => $page,
            'per_page' => $perPage,
        ]);

        if ($response->successful()) {
            return $response->json() ?? ['data' => [], 'meta' => []];
        }

        return ['data' => [], 'meta' => []];
    }

    public function getPostComments(int $postId, int $page = 1, int $perPage = 5): array
    {
        $response = $this->http()->get("{$this->baseUrl}/comments", [
            'post_id' => $postId,
            'status' => 'approved',
            'sort_by' => 'created_at',
            'sort_order' => 'desc',
            'page' => $page,
            'per_page' => $perPage,
            'with' => 'author',
        ]);

        if ($response->successful()) {
            return $response->json() ?? ['data' => [], 'meta' => []];
        }

        return ['data' => [], 'meta' => []];
    }

    public function getUserComments(int $userId, int $page = 1): array
    {
        $response = $this->http()->get("{$this->baseUrl}/comments", [
            'author_id' => $userId,
            'with' => 'post',
            'page' => $page,
            'per_page' => 15,
        ]);

        if ($response->successful()) {
            return $response->json() ?? ['data' => [], 'meta' => []];
        }

        return ['data' => [], 'meta' => []];
    }

    public function getMedia(array $query = []): array
    {
        $response = $this->http()->get("{$this->baseUrl}/media", array_merge([
            'per_page' => 12,
        ], $query));

        if ($response->successful()) {
            return $response->json() ?? ['data' => [], 'meta' => []];
        }

        return ['data' => [], 'meta' => []];
    }

    public function createPost(array $data): array
    {
        $response = $this->http()->post("{$this->baseUrl}/posts", $data);

        return [
            'success' => $response->successful(),
            'data' => $response->json('data'),
            'errors' => $response->json('errors') ?? [],
        ];
    }

    public function updatePost(int $id, array $data): array
    {
        $response = $this->http()->put("{$this->baseUrl}/posts/{$id}", $data);

        return [
            'success' => $response->successful(),
            'data' => $response->json('data'),
            'errors' => $response->json('errors') ?? [],
        ];
    }

    public function deletePost(int $id): array
    {
        $response = $this->http()->delete("{$this->baseUrl}/posts/{$id}");

        return [
            'success' => $response->successful(),
            'errors' => $response->json('errors') ?? [],
        ];
    }

    public function createComment(int $postId, string $content): array
    {
        $response = $this->http()->post("{$this->baseUrl}/comments", [
            'post_id' => $postId,
            'content' => $content,
        ]);

        return [
            'success' => $response->successful(),
            'data'    => $response->json('data'),
            'errors'  => $response->json('errors') ?? [],
        ];
    }

    public function approveComment(int $commentId): bool
    {
        $response = $this->http()->patch("{$this->baseUrl}/comments/{$commentId}/approve");

        return $response->successful();
    }

    public function subscribeNewsletter(string $email): array
    {
        $response = $this->http()->post("{$this->baseUrl}/newsletter/subscribe", [
            'email' => $email,
        ]);

        return [
            'success' => $response->successful(),
            'message' => $response->json('message') ?? '',
            'errors' => $response->json('errors') ?? [],
        ];
    }
}
