<?php

namespace Tests\Unit;

use App\Services\BlogApiService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BlogApiServiceSearchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['services.blog.url' => 'http://blog-test']);
        Cache::flush();
    }

    // -------------------------------------------------------------------------
    // search()
    // -------------------------------------------------------------------------

    #[Test]
    public function search_returns_grouped_results_on_success(): void
    {
        Http::fake([
            'blog-test/api/v1/search*' => Http::response([
                'query'      => 'laravel',
                'posts'      => [['id' => 1, 'title' => 'Laravel tips', 'slug' => 'laravel-tips']],
                'categories' => [['id' => 2, 'name' => 'Backend']],
                'tags'       => [['id' => 3, 'name' => 'laravel']],
            ], 200),
        ]);

        $service = app(BlogApiService::class);
        $result  = $service->search('laravel', 'pl');

        $this->assertArrayHasKey('posts', $result);
        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('tags', $result);
        $this->assertCount(1, $result['posts']);
        $this->assertEquals('Laravel tips', $result['posts'][0]['title']);
    }

    #[Test]
    public function search_returns_empty_arrays_on_failure(): void
    {
        Http::fake([
            'blog-test/api/v1/search*' => Http::response(null, 503),
        ]);

        $service = app(BlogApiService::class);
        $result  = $service->search('laravel', 'pl');

        $this->assertSame([], $result['posts']);
        $this->assertSame([], $result['categories']);
        $this->assertSame([], $result['tags']);
    }

    #[Test]
    public function search_passes_query_and_locale_as_params(): void
    {
        Http::fake([
            'blog-test/api/v1/search*' => Http::response([
                'posts' => [], 'categories' => [], 'tags' => [],
            ], 200),
        ]);

        $service = app(BlogApiService::class);
        $service->search('docker', 'en');

        Http::assertSent(fn ($request) =>
            str_contains($request->url(), '/api/v1/search') &&
            $request->data()['q'] === 'docker' &&
            $request->data()['locale'] === 'en'
        );
    }

    // -------------------------------------------------------------------------
    // getPosts()
    // -------------------------------------------------------------------------

    #[Test]
    public function get_posts_returns_data_and_meta_on_success(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response([
                'data' => [
                    ['id' => 1, 'title' => 'First', 'slug' => 'first'],
                    ['id' => 2, 'title' => 'Second', 'slug' => 'second'],
                ],
                'meta' => ['total' => 2, 'current_page' => 1, 'last_page' => 1, 'per_page' => 10],
            ], 200),
        ]);

        $service = app(BlogApiService::class);
        $result  = $service->getPosts();

        $this->assertCount(2, $result['data']);
        $this->assertEquals(2, $result['meta']['total']);
    }

    #[Test]
    public function get_posts_returns_empty_on_failure(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response(null, 500),
        ]);

        $service = app(BlogApiService::class);
        $result  = $service->getPosts();

        $this->assertSame([], $result['data']);
        $this->assertSame([], $result['meta']);
    }

    #[Test]
    public function get_posts_passes_status_published_always(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response(['data' => [], 'meta' => []], 200),
        ]);

        $service = app(BlogApiService::class);
        $service->getPosts();

        Http::assertSent(fn ($request) =>
            $request->data()['status'] === 'published'
        );
    }

    #[Test]
    public function get_posts_passes_search_query_when_provided(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response(['data' => [], 'meta' => []], 200),
        ]);

        $service = app(BlogApiService::class);
        $service->getPosts(search: 'laravel');

        Http::assertSent(fn ($request) =>
            isset($request->data()['search']) && $request->data()['search'] === 'laravel'
        );
    }

    #[Test]
    public function get_posts_omits_search_param_when_null(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response(['data' => [], 'meta' => []], 200),
        ]);

        $service = app(BlogApiService::class);
        $service->getPosts(search: null);

        Http::assertSent(fn ($request) =>
            !isset($request->data()['search'])
        );
    }

    #[Test]
    public function get_posts_passes_category_id_when_provided(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response(['data' => [], 'meta' => []], 200),
        ]);

        $service = app(BlogApiService::class);
        $service->getPosts(categoryId: 5);

        Http::assertSent(fn ($request) =>
            isset($request->data()['category_id']) && (int) $request->data()['category_id'] === 5
        );
    }

    #[Test]
    public function get_posts_passes_tag_id_when_provided(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response(['data' => [], 'meta' => []], 200),
        ]);

        $service = app(BlogApiService::class);
        $service->getPosts(tagId: 3);

        Http::assertSent(fn ($request) =>
            isset($request->data()['tag_id']) && (int) $request->data()['tag_id'] === 3
        );
    }

    #[Test]
    public function get_posts_passes_sort_params(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response(['data' => [], 'meta' => []], 200),
        ]);

        $service = app(BlogApiService::class);
        $service->getPosts(sortBy: 'published_at', sortOrder: 'asc');

        Http::assertSent(fn ($request) =>
            $request->data()['sort_by'] === 'published_at' &&
            $request->data()['sort_order'] === 'asc'
        );
    }

    #[Test]
    public function get_posts_defaults_to_newest_first(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response(['data' => [], 'meta' => []], 200),
        ]);

        $service = app(BlogApiService::class);
        $service->getPosts();

        Http::assertSent(fn ($request) =>
            $request->data()['sort_order'] === 'desc'
        );
    }
}
