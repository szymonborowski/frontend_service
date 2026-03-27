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
                'name' => 'TestUser',
                'email' => 'test@example.com',
            ],
        ];
    }

    #[Test]
    public function me_show_redirects_to_login_when_no_access_token(): void
    {
        $response = $this->get(route('panel.profile'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function me_show_returns_view_with_session_user(): void
    {
        $this->withSession($this->authenticatedSession());

        $response = $this->get(route('panel.profile'));

        $response->assertOk();
        $response->assertViewIs('panel.profile');
        $response->assertViewHas('user', ['id' => 1, 'name' => 'TestUser', 'email' => 'test@example.com']);
    }

    #[Test]
    public function me_update_profile_redirects_to_login_when_no_access_token(): void
    {
        $response = $this->post(route('panel.profile.update'), [
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

        $response = $this->post(route('panel.profile.update'), [
            'name' => 'Updated',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('Updated', session('user')['name']);
    }

    #[Test]
    public function me_update_profile_returns_error_when_no_user_in_session(): void
    {
        $this->withSession(['access_token' => 'token']);

        $response = $this->post(route('panel.profile.update'), [
            'name' => 'Updated',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('profile');
    }

    #[Test]
    public function me_update_password_redirects_to_login_when_no_access_token(): void
    {
        $response = $this->put(route('panel.password.update'), [
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
            'http://users-test/api/internal/auth/check' => Http::response(['authorized' => true], 200),
            'http://users-test/api/internal/users/1' => Http::response(['data' => ['id' => 1]], 200),
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
    public function me_update_password_fails_when_current_password_invalid(): void
    {
        $this->withSession($this->authenticatedSession());

        Http::fake([
            'http://users-test/api/*' => Http::response(['authorized' => false], 200),
        ]);

        $response = $this->put(route('panel.password.update'), [
            'current_password' => 'wrong',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('current_password');
    }
}
