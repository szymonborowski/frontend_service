<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PostsControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['services.blog.url' => 'http://blog-test']);
    }

    private function fakeBlogApi(array $posts = [], array $meta = [], array $overrides = []): void
    {
        $defaultMeta = array_merge([
            'total'        => count($posts),
            'current_page' => 1,
            'last_page'    => 1,
            'per_page'     => 10,
        ], $meta);

        Http::fake(array_merge([
            'blog-test/api/v1/posts*' => Http::response([
                'data' => $posts,
                'meta' => $defaultMeta,
            ], 200),
            'blog-test/api/v1/categories*' => Http::response(['data' => []], 200),
            'blog-test/api/v1/tags*'       => Http::response(['data' => []], 200),
        ], $overrides));
    }

    // -------------------------------------------------------------------------
    // Basic rendering
    // -------------------------------------------------------------------------

    #[Test]
    public function index_renders_posts_view(): void
    {
        $this->fakeBlogApi();

        $this->get(route('posts.index'))
            ->assertOk()
            ->assertViewIs('posts');
    }

    #[Test]
    public function index_passes_posts_and_meta_to_view(): void
    {
        $posts = [
            ['id' => 1, 'title' => 'First post', 'slug' => 'first', 'published_at' => now()->toDateTimeString(), 'categories' => [], 'tags' => []],
            ['id' => 2, 'title' => 'Second post', 'slug' => 'second', 'published_at' => now()->toDateTimeString(), 'categories' => [], 'tags' => []],
        ];

        $this->fakeBlogApi($posts);

        $response = $this->get(route('posts.index'))->assertOk();

        $this->assertCount(2, $response->viewData('posts'));
        $this->assertArrayHasKey('total', $response->viewData('meta'));
    }

    #[Test]
    public function index_passes_categories_and_tags_to_view(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*'      => Http::response(['data' => [], 'meta' => []], 200),
            'blog-test/api/v1/categories*' => Http::response(['data' => [['id' => 1, 'name' => 'PHP', 'slug' => 'php']]], 200),
            'blog-test/api/v1/tags*'       => Http::response(['data' => [['id' => 1, 'name' => 'laravel', 'slug' => 'laravel']]], 200),
        ]);

        $response = $this->get(route('posts.index'))->assertOk();

        $this->assertNotEmpty($response->viewData('categories'));
        $this->assertNotEmpty($response->viewData('tags'));
    }

    // -------------------------------------------------------------------------
    // Default values
    // -------------------------------------------------------------------------

    #[Test]
    public function index_defaults_to_sort_desc_by_published_at(): void
    {
        $this->fakeBlogApi();

        $response = $this->get(route('posts.index'))->assertOk();

        $this->assertEquals('published_at', $response->viewData('sortBy'));
        $this->assertEquals('desc', $response->viewData('sortOrder'));
    }

    #[Test]
    public function index_defaults_to_per_page_10(): void
    {
        $this->fakeBlogApi();

        $response = $this->get(route('posts.index'))->assertOk();

        $this->assertEquals(10, $response->viewData('currentPerPage'));
    }

    #[Test]
    public function index_search_and_active_filters_are_null_by_default(): void
    {
        $this->fakeBlogApi();

        $response = $this->get(route('posts.index'))->assertOk();

        $this->assertNull($response->viewData('search'));
        $this->assertNull($response->viewData('categoryId'));
        $this->assertNull($response->viewData('tagId'));
        $this->assertNull($response->viewData('activeCategory'));
        $this->assertNull($response->viewData('activeTag'));
    }

    // -------------------------------------------------------------------------
    // Query parameter handling
    // -------------------------------------------------------------------------

    #[Test]
    public function index_passes_search_query_to_view(): void
    {
        $this->fakeBlogApi();

        $response = $this->get(route('posts.index', ['q' => 'laravel']))->assertOk();

        $this->assertEquals('laravel', $response->viewData('search'));
    }

    #[Test]
    public function index_passes_category_id_to_view(): void
    {
        $this->fakeBlogApi();

        $response = $this->get(route('posts.index', ['category_id' => 7]))->assertOk();

        $this->assertEquals(7, $response->viewData('categoryId'));
    }

    #[Test]
    public function index_passes_tag_id_to_view(): void
    {
        $this->fakeBlogApi();

        $response = $this->get(route('posts.index', ['tag_id' => 3]))->assertOk();

        $this->assertEquals(3, $response->viewData('tagId'));
    }

    #[Test]
    public function index_accepts_sort_order_asc(): void
    {
        $this->fakeBlogApi();

        $response = $this->get(route('posts.index', ['sort_order' => 'asc']))->assertOk();

        $this->assertEquals('asc', $response->viewData('sortOrder'));
    }

    #[Test]
    public function index_rejects_invalid_sort_order_and_falls_back_to_desc(): void
    {
        $this->fakeBlogApi();

        $response = $this->get(route('posts.index', ['sort_order' => 'random']))->assertOk();

        $this->assertEquals('desc', $response->viewData('sortOrder'));
    }

    #[Test]
    public function index_rejects_invalid_per_page_and_falls_back_to_10(): void
    {
        $this->fakeBlogApi();

        $response = $this->get(route('posts.index', ['per_page' => 999]))->assertOk();

        $this->assertEquals(10, $response->viewData('currentPerPage'));
    }

    #[Test]
    public function index_resolves_active_category_name_from_category_list(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*' => Http::response(['data' => [], 'meta' => []], 200),
            'blog-test/api/v1/categories*' => Http::response([
                'data' => [['id' => 5, 'name' => 'DevOps', 'slug' => 'devops']],
            ], 200),
            'blog-test/api/v1/tags*' => Http::response(['data' => []], 200),
        ]);

        $response = $this->get(route('posts.index', ['category_id' => 5]))->assertOk();

        $this->assertNotNull($response->viewData('activeCategory'));
        $this->assertEquals('DevOps', $response->viewData('activeCategory')['name']);
    }

    #[Test]
    public function index_resolves_active_tag_name_from_tag_list(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*'      => Http::response(['data' => [], 'meta' => []], 200),
            'blog-test/api/v1/categories*' => Http::response(['data' => []], 200),
            'blog-test/api/v1/tags*'       => Http::response([
                'data' => [['id' => 8, 'name' => 'docker', 'slug' => 'docker']],
            ], 200),
        ]);

        $response = $this->get(route('posts.index', ['tag_id' => 8]))->assertOk();

        $this->assertNotNull($response->viewData('activeTag'));
        $this->assertEquals('docker', $response->viewData('activeTag')['name']);
    }

    // -------------------------------------------------------------------------
    // extraParams for pagination
    // -------------------------------------------------------------------------

    #[Test]
    public function extra_params_include_active_search_query(): void
    {
        $this->fakeBlogApi();

        $response = $this->get(route('posts.index', ['q' => 'php']))->assertOk();

        $this->assertArrayHasKey('q', $response->viewData('extraParams'));
        $this->assertEquals('php', $response->viewData('extraParams')['q']);
    }

    #[Test]
    public function extra_params_exclude_default_sort_values(): void
    {
        $this->fakeBlogApi();

        // Default sort_order=desc should NOT appear in extraParams (to keep URLs clean)
        $response = $this->get(route('posts.index'))->assertOk();

        $this->assertArrayNotHasKey('sort_order', $response->viewData('extraParams'));
        $this->assertArrayNotHasKey('sort_by', $response->viewData('extraParams'));
    }

    #[Test]
    public function extra_params_include_non_default_sort_order(): void
    {
        $this->fakeBlogApi();

        $response = $this->get(route('posts.index', ['sort_order' => 'asc']))->assertOk();

        $this->assertArrayHasKey('sort_order', $response->viewData('extraParams'));
    }

    // -------------------------------------------------------------------------
    // Blog API failure handling
    // -------------------------------------------------------------------------

    #[Test]
    public function index_renders_with_empty_posts_when_blog_api_fails(): void
    {
        Http::fake([
            'blog-test/api/v1/posts*'      => Http::response(null, 503),
            'blog-test/api/v1/categories*' => Http::response(['data' => []], 200),
            'blog-test/api/v1/tags*'       => Http::response(['data' => []], 200),
        ]);

        $response = $this->get(route('posts.index'))->assertOk();

        $this->assertSame([], $response->viewData('posts'));
    }
}
