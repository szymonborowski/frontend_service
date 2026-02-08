<?php

namespace Tests\Unit;

use App\Services\UsersApiService;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UsersApiServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['services.users.url' => 'http://users-test']);
    }

    #[Test]
    public function get_user_returns_user_on_success(): void
    {
        Http::fake([
            'http://users-test/api/*' => Http::response([
                'data' => [
                    'id' => 1,
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                ],
            ], 200),
        ]);

        $service = app(UsersApiService::class);
        $result = $service->getUser(1);

        $this->assertNotNull($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('test@example.com', $result['email']);
    }

    #[Test]
    public function get_user_returns_null_on_failure(): void
    {
        Http::fake([
            'http://users-test/api/*' => Http::response(null, 404),
        ]);

        $service = app(UsersApiService::class);
        $result = $service->getUser(999);

        $this->assertNull($result);
    }

    #[Test]
    public function update_user_returns_success(): void
    {
        Http::fake([
            'http://users-test/api/*' => Http::response([
                'data' => ['id' => 1, 'name' => 'Updated'],
            ], 200),
        ]);

        $service = app(UsersApiService::class);
        $result = $service->updateUser(1, ['name' => 'Updated']);

        $this->assertTrue($result['success']);
        $this->assertEquals(200, $result['status']);
    }

    #[Test]
    public function update_user_returns_errors_on_validation_failure(): void
    {
        Http::fake([
            'http://users-test/api/*' => Http::response([
                'errors' => ['email' => ['Invalid']],
            ], 422),
        ]);

        $service = app(UsersApiService::class);
        $result = $service->updateUser(1, ['email' => 'bad']);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('email', $result['errors']);
    }

    #[Test]
    public function verify_password_returns_true_when_authorised(): void
    {
        Http::fake([
            'http://users-test/api/*' => Http::response(['authorised' => true], 200),
        ]);

        $service = app(UsersApiService::class);
        $result = $service->verifyPassword('test@example.com', 'password');

        $this->assertTrue($result);
    }

    #[Test]
    public function verify_password_returns_false_when_not_authorised(): void
    {
        Http::fake([
            'http://users-test/api/*' => Http::response(['authorised' => false], 200),
        ]);

        $service = app(UsersApiService::class);
        $result = $service->verifyPassword('test@example.com', 'wrong');

        $this->assertFalse($result);
    }

    #[Test]
    public function update_password_returns_success(): void
    {
        Http::fake([
            'http://users-test/api/*' => Http::response(['data' => ['id' => 1]], 200),
        ]);

        $service = app(UsersApiService::class);
        $result = $service->updatePassword(1, 'NewPassword123!');

        $this->assertTrue($result['success']);
    }
}
