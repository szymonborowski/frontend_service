<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OAuthControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.sso.url' => 'https://sso.test',
            'services.sso.internal_url' => 'https://sso-internal.test',
            'services.sso.client_id' => 'frontend-client',
            'services.sso.client_secret' => 'secret',
            'services.sso.redirect_uri' => 'https://frontend.test/oauth/callback',
        ]);
    }

    #[Test]
    public function login_redirects_to_sso_with_state_in_session(): void
    {
        $response = $this->get(route('login'));

        $response->assertRedirect();
        $this->assertStringStartsWith('https://sso.test/oauth/authorize?', $response->headers->get('Location'));
        $this->assertArrayHasKey('oauth_state', session()->all());
        $this->assertSame(40, strlen(session('oauth_state')));
    }

    #[Test]
    public function register_redirects_to_sso_register_with_redirect_uri(): void
    {
        $response = $this->get(route('register'));

        $response->assertRedirect();
        $redirectUrl = $response->headers->get('Location');
        $this->assertStringStartsWith('https://sso.test/register?redirect_uri=', $redirectUrl);
    }

    #[Test]
    public function callback_aborts_with_invalid_state(): void
    {
        $response = $this->get('/oauth/callback?state=wrong&code=abc');

        $response->assertStatus(400);
    }

    #[Test]
    public function callback_aborts_when_state_missing(): void
    {
        $response = $this->get('/oauth/callback?code=abc');

        $response->assertStatus(400);
    }

    #[Test]
    public function callback_aborts_when_error_in_query(): void
    {
        $this->withSession(['oauth_state' => 'valid-state']);
        $response = $this->get('/oauth/callback?state=valid-state&error=access_denied&error_description=User+denied');

        $response->assertStatus(400);
    }

    #[Test]
    public function callback_stores_tokens_and_redirects_on_success(): void
    {
        $this->withSession(['oauth_state' => 'valid-state']);

        Http::fake([
            'sso-internal.test/oauth/token' => Http::response([
                'access_token' => 'token-123',
                'refresh_token' => 'refresh-456',
                'token_type' => 'Bearer',
                'expires_in' => 3600,
            ], 200),
            'sso-internal.test/api/user' => Http::response([
                'id' => 1,
                'name' => 'Test User',
                'email' => 'test@example.com',
                'created_at' => '2026-01-01T00:00:00.000000Z',
            ], 200),
        ]);

        $response = $this->get('/oauth/callback?state=valid-state&code=auth-code');

        $response->assertRedirect(route('home'));
        $this->assertEquals('token-123', session('access_token'));
        $this->assertEquals('refresh-456', session('refresh_token'));
        $this->assertEquals(1, session('user')['id']);
        $this->assertEquals('Test User', session('user')['name']);
    }

    #[Test]
    public function callback_aborts_when_token_exchange_fails(): void
    {
        $this->withSession(['oauth_state' => 'valid-state']);

        Http::fake([
            'sso-internal.test/oauth/token' => Http::response(null, 400),
        ]);

        $response = $this->get('/oauth/callback?state=valid-state&code=bad-code');

        $response->assertStatus(400);
    }

    #[Test]
    public function logout_clears_session_and_redirects_to_sso_logout(): void
    {
        $this->withSession([
            'access_token' => 'token',
            'refresh_token' => 'refresh',
            'user' => ['id' => 1, 'name' => 'Test'],
        ]);

        $response = $this->post(route('logout'));

        $response->assertRedirect();
        $this->assertStringContainsString('/logout?redirect_uri=', $response->headers->get('Location'));
        $this->assertNull(session('access_token'));
        $this->assertNull(session('user'));
    }
}
