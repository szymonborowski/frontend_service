<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BlogApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.blog.url') . '/api/v1';
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
}
