<?php

namespace Tests\Unit;

use App\Services\BlogApiService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BlogApiServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['services.blog.url' => 'http://blog-test']);
        Cache::flush();
    }

    #[Test]
    public function get_recent_posts_returns_posts_on_success(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response([
                'data' => [
                    ['id' => 1, 'title' => 'First', 'slug' => 'first'],
                    ['id' => 2, 'title' => 'Second', 'slug' => 'second'],
                ],
            ], 200),
        ]);

        $service = app(BlogApiService::class);
        $result = $service->getRecentPosts(10);

        $this->assertCount(2, $result);
        $this->assertEquals('First', $result[0]['title']);
    }

    #[Test]
    public function get_recent_posts_returns_empty_on_failure(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response(null, 500),
        ]);

        $service = app(BlogApiService::class);
        $result = $service->getRecentPosts(10);

        $this->assertSame([], $result);
    }

    #[Test]
    public function get_categories_returns_data_on_success(): void
    {
        Http::fake([
            'blog-test/api/v1/categories*' => Http::response([
                'data' => [['id' => 1, 'name' => 'Tech']],
            ], 200),
        ]);

        $service = app(BlogApiService::class);
        $result = $service->getCategories();

        $this->assertCount(1, $result);
        $this->assertEquals('Tech', $result[0]['name']);
    }

    #[Test]
    public function get_tags_returns_data_on_success(): void
    {
        Http::fake([
            'blog-test/api/v1/tags*' => Http::response([
                'data' => [['id' => 1, 'name' => 'laravel']],
            ], 200),
        ]);

        $service = app(BlogApiService::class);
        $result = $service->getTags();

        $this->assertCount(1, $result);
        $this->assertEquals('laravel', $result[0]['name']);
    }

    #[Test]
    public function get_post_by_slug_returns_post_on_success(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response([
                'data' => [['id' => 1, 'title' => 'My Post', 'slug' => 'my-post']],
            ], 200),
        ]);

        $service = app(BlogApiService::class);
        $result = $service->getPost('my-post');

        $this->assertNotNull($result);
        $this->assertEquals('my-post', $result['slug']);
    }

    #[Test]
    public function get_post_by_slug_returns_null_when_empty(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response(['data' => []], 200),
        ]);

        $service = app(BlogApiService::class);
        $result = $service->getPost('missing');

        $this->assertNull($result);
    }

    #[Test]
    public function get_post_by_id_returns_post_on_success(): void
    {
        Http::fake([
            'blog-test/api/v1/posts/1*' => Http::response([
                'data' => ['id' => 1, 'title' => 'Post', 'slug' => 'post'],
            ], 200),
        ]);

        $service = app(BlogApiService::class);
        $result = $service->getPostById(1);

        $this->assertNotNull($result);
        $this->assertEquals(1, $result['id']);
    }

    #[Test]
    public function create_post_returns_success_and_data(): void
    {
        Http::fake([
            'blog-test/api/v1/posts' => Http::response([
                'data' => ['id' => 1, 'title' => 'New', 'slug' => 'new'],
            ], 201),
        ]);

        $service = app(BlogApiService::class);
        $result = $service->createPost(['title' => 'New', 'content' => 'Body']);

        $this->assertTrue($result['success']);
        $this->assertEquals('New', $result['data']['title']);
    }

    #[Test]
    public function delete_post_returns_success(): void
    {
        Http::fake([
            'blog-test/api/v1/posts/1' => Http::response(null, 204),
        ]);

        $service = app(BlogApiService::class);
        $result = $service->deletePost(1);

        $this->assertTrue($result['success']);
    }
}
