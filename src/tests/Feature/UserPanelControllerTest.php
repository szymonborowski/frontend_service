<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserPanelControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.users.url' => 'http://users-test',
            'services.blog.url' => 'http://blog-test',
        ]);
    }

    private function authenticatedSession(): array
    {
        return [
            'access_token' => 'token',
            'user' => [
                'id' => 1,
                'name' => 'Test User',
                'email' => 'test@example.com',
            ],
        ];
    }

    #[Test]
    public function profile_returns_view_with_session_user(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake(['http://users-test/api/*' => Http::response([], 200)]);

        $response = $this->get(route('panel.profile'));

        $response->assertOk();
        $response->assertViewIs('panel.profile');
        $response->assertViewHas('user', ['id' => 1, 'name' => 'Test User', 'email' => 'test@example.com']);
    }

    #[Test]
    public function update_profile_success(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'http://users-test/api/*' => Http::response([
                'data' => ['id' => 1, 'name' => 'Updated', 'email' => 'updated@example.com'],
            ], 200),
        ]);

        $response = $this->put(route('panel.profile.update'), [
            'name' => 'Updated',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('Updated', session('user')['name']);
    }

    #[Test]
    public function update_profile_returns_error_when_no_user_in_session(): void
    {
        $this->withSession(['access_token' => 'token']); // no 'user' key

        $response = $this->put(route('panel.profile.update'), [
            'name' => 'Updated',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('profile');
    }

    #[Test]
    public function update_password_success(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'http://users-test/api/auth/check' => Http::response(['authorised' => true], 200),
            'http://users-test/api/users/1' => Http::response(['data' => ['id' => 1]], 200),
        ]);

        $response = $this->put(route('panel.password.update'), [
            'current_password' => 'oldpass',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    #[Test]
    public function update_password_fails_when_current_password_invalid(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'http://users-test/api/*' => Http::response(['authorised' => false], 200),
        ]);

        $response = $this->put(route('panel.password.update'), [
            'current_password' => 'wrong',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('current_password');
    }

    #[Test]
    public function posts_returns_view_with_user_posts(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'http://blog-test/api/v1/posts*' => Http::response([
                'data' => [['id' => 1, 'title' => 'My Post']],
                'meta' => [],
            ], 200),
        ]);

        $response = $this->get(route('panel.posts'));

        $response->assertOk();
        $response->assertViewIs('panel.posts.index');
        $response->assertViewHas('posts');
    }

    #[Test]
    public function create_post_returns_view_with_categories_and_tags(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'http://blog-test/api/v1/categories*' => Http::response(['data' => [['id' => 1, 'name' => 'Tech']]], 200),
            'http://blog-test/api/v1/tags*' => Http::response(['data' => [['id' => 1, 'name' => 'laravel']]], 200),
        ]);

        $response = $this->get(route('panel.posts.create'));

        $response->assertOk();
        $response->assertViewIs('panel.posts.create');
        $response->assertViewHas('categories');
        $response->assertViewHas('tags');
    }

    #[Test]
    public function store_post_success(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'http://blog-test/api/v1/posts' => Http::response([
                'data' => ['id' => 1, 'title' => 'New Post'],
            ], 201),
        ]);

        $response = $this->post(route('panel.posts.store'), [
            'title' => 'New Post',
            'content' => 'Content here',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('panel.posts'));
        $response->assertSessionHas('success');
    }

    #[Test]
    public function delete_post_success(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'http://blog-test/api/v1/posts/1' => Http::response(null, 204),
        ]);

        $response = $this->delete(route('panel.posts.delete', ['id' => 1]));

        $response->assertRedirect(route('panel.posts'));
        $response->assertSessionHas('success');
    }

    #[Test]
    public function comments_returns_view_with_user_comments(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'http://blog-test/api/v1/comments*' => Http::response([
                'data' => [
                    ['id' => 1, 'content' => 'A comment', 'created_at' => now()->toISOString(), 'status' => 'approved'],
                ],
                'meta' => [],
            ], 200),
        ]);

        $response = $this->get(route('panel.comments'));

        $response->assertOk();
        $response->assertViewIs('panel.comments');
        $response->assertViewHas('comments');
    }
}
