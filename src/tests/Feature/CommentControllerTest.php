<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['services.blog.url' => 'http://blog-test']);
    }

    private function authenticatedSession(array $roles = ['writer']): array
    {
        return [
            'access_token' => 'fake-token',
            'user'         => ['id' => 1, 'name' => 'Test User', 'roles' => $roles],
        ];
    }

    #[Test]
    public function unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->post(route('post.comments.store', 1), [
            'content' => 'Hello there',
        ]);

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function guest_role_user_receives_403(): void
    {
        $this->withSession($this->authenticatedSession(['guest']));

        $response = $this->post(route('post.comments.store', 1), [
            'content' => 'Hello there',
        ]);

        $response->assertForbidden();
    }

    #[Test]
    public function validation_fails_when_content_too_short(): void
    {
        $this->withSession($this->authenticatedSession());

        $response = $this->post(route('post.comments.store', 1), [
            'content' => 'Hi',
        ]);

        $response->assertSessionHasErrors('content');
    }

    #[Test]
    public function validation_fails_when_content_too_long(): void
    {
        $this->withSession($this->authenticatedSession());

        $response = $this->post(route('post.comments.store', 1), [
            'content' => str_repeat('a', 5001),
        ]);

        $response->assertSessionHasErrors('content');
    }

    #[Test]
    public function successful_submission_redirects_with_flash(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'blog-test/api/v1/comments'   => Http::response(['data' => ['id' => 10]], 201),
            'blog-test/api/v1/comments/*' => Http::response(['data' => ['id' => 10, 'status' => 'approved']], 200),
        ]);

        $response = $this->post(route('post.comments.store', 1), [
            'content' => 'This is a valid comment.',
        ]);

        $response->assertRedirect();
        $this->assertTrue(session('comment_success'));
    }

    #[Test]
    public function blog_api_failure_redirects_with_error(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'blog-test/api/v1/comments' => Http::response([
                'errors' => ['content' => ['The content field is required.']],
            ], 422),
        ]);

        $response = $this->post(route('post.comments.store', 1), [
            'content' => 'This is a valid comment.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('comment_content');
    }
}
