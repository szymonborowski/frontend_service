<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class UsersApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.users.url') . '/api';
    }

    protected function http(): PendingRequest
    {
        $token = session('access_token');

        if ($token) {
            return Http::withToken($token);
        }

        return Http::withHeaders([]);
    }

    public function getUser(int $id): ?array
    {
        $response = $this->http()->get("{$this->baseUrl}/users/{$id}");

        if ($response->successful()) {
            return $response->json('data');
        }

        return null;
    }

    public function updateUser(int $id, array $data): array
    {
        $response = $this->http()->put("{$this->baseUrl}/users/{$id}", $data);

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'errors' => $response->json('errors') ?? [],
        ];
    }

    public function verifyPassword(string $email, string $password): bool
    {
        $response = $this->http()->post("{$this->baseUrl}/auth/check", [
            'email' => $email,
            'password' => $password,
        ]);

        return $response->successful() && ($response->json('authorised') === true);
    }

    public function updatePassword(int $id, string $password): array
    {
        $response = $this->http()->put("{$this->baseUrl}/users/{$id}", [
            'password' => $password,
        ]);

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'errors' => $response->json('errors') ?? [],
        ];
    }
}
