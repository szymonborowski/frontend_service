<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EnsureAuthenticatedMiddlewareTest extends TestCase
{
    #[Test]
    public function panel_redirects_to_login_when_no_access_token(): void
    {
        $response = $this->get(route('panel.profile'));

        $response->assertRedirect(route('login'));
        $this->assertEquals(route('panel.profile'), session('redirect_after_login'));
    }

    #[Test]
    public function panel_allows_access_when_session_has_access_token(): void
    {
        $this->withSession([
            'access_token' => 'fake-token',
            'user' => ['id' => 1, 'name' => 'Test', 'email' => 'test@example.com'],
        ]);

        $response = $this->get(route('panel.profile'));

        $response->assertOk();
    }
}
