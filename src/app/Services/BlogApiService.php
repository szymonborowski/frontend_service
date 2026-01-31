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

        if ($token) {
            return Http::withToken($token);
        }

        return Http::withHeaders([]);
    }

    public function getRecentPosts(int $limit = 10): array
    {
        return Cache::remember("blog.posts.recent.{$limit}", 300, function () use ($limit) {
            $response = Http::get("{$this->baseUrl}/posts", [
                'per_page' => $limit,
                'status' => 'published',
                'with' => 'categories,tags',
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
            $response = Http::get("{$this->baseUrl}/categories", [
                'with_count' => 'posts',
            ]);

            if ($response->successful()) {
                return $response->json('data') ?? [];
            }

            return [];
        });
    }

    public function getTags(): array
    {
        return Cache::remember('blog.tags', 300, function () {
            $response = Http::get("{$this->baseUrl}/tags");

            if ($response->successful()) {
                return $response->json('data') ?? [];
            }

            return [];
        });
    }

    public function getPost(string $slug): ?array
    {
        $response = Http::get("{$this->baseUrl}/posts", [
            'slug' => $slug,
            'with' => 'categories,tags',
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
            'with' => 'categories,tags',
        ]);

        if ($response->successful()) {
            return $response->json('data') ?? $response->json();
        }

        return null;
    }

    public function getUserPosts(int $userId, int $page = 1): array
    {
        $response = $this->http()->get("{$this->baseUrl}/posts", [
            'author_id' => $userId,
            'with' => 'categories,tags',
            'page' => $page,
            'per_page' => 15,
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
}
