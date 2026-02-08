<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MeControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['services.users.url' => 'http://users-test']);
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
    public function me_show_redirects_to_login_when_no_access_token(): void
    {
        $response = $this->get(route('me'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function me_show_returns_view_with_session_user(): void
    {
        $this->withSession($this->authenticatedSession());

        $response = $this->get(route('me'));

        $response->assertOk();
        $response->assertViewIs('me');
        $response->assertViewHas('user', ['id' => 1, 'name' => 'Test User', 'email' => 'test@example.com']);
    }

    #[Test]
    public function me_update_profile_redirects_to_login_when_no_access_token(): void
    {
        $response = $this->put(route('me.profile'), [
            'name' => 'Updated',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function me_update_profile_success(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'http://users-test/api/*' => Http::response([
                'data' => ['id' => 1, 'name' => 'Updated', 'email' => 'updated@example.com'],
            ], 200),
        ]);

        $response = $this->put(route('me.profile'), [
            'name' => 'Updated',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('profile_success');
        $this->assertEquals('Updated', session('user')['name']);
    }

    #[Test]
    public function me_update_profile_returns_error_when_no_user_in_session(): void
    {
        $this->withSession(['access_token' => 'token']);

        $response = $this->put(route('me.profile'), [
            'name' => 'Updated',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('profile');
    }

    #[Test]
    public function me_update_password_redirects_to_login_when_no_access_token(): void
    {
        $response = $this->put(route('me.password'), [
            'current_password' => 'old',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function me_update_password_success(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'http://users-test/api/auth/check' => Http::response(['authorised' => true], 200),
            'http://users-test/api/users/1' => Http::response(['data' => ['id' => 1]], 200),
        ]);

        $response = $this->put(route('me.password'), [
            'current_password' => 'oldpass',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('password_success');
    }

    #[Test]
    public function me_update_password_fails_when_current_password_invalid(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'http://users-test/api/*' => Http::response(['authorised' => false], 200),
        ]);

        $response = $this->put(route('me.password'), [
            'current_password' => 'wrong',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('current_password');
    }
}
