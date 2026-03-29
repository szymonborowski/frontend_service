<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AnalyticsApiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.analytics.url'), '/') . '/api/v1';
    }

    private function http()
    {
        return Http::withHeaders([
            'X-Internal-Api-Key' => config('services.analytics.internal_api_key'),
            'Accept' => 'application/json',
        ]);
    }

    public function getPostStats(string $postUuid, string $period = 'all'): array
    {
        try {
            $response = $this->http()->get("{$this->baseUrl}/posts/{$postUuid}/stats", [
                'period' => $period,
            ]);

            if ($response->successful()) {
                return $response->json() ?? [];
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return ['total_views' => 0, 'unique_viewers' => 0, 'daily_stats' => []];
    }

    public function toggleLike(string $type, string $id, string $ipAddress, ?int $userId = null): ?array
    {
        try {
            $payload = [
                'likeable_type' => $type,
                'likeable_id' => $id,
                'ip_address' => $ipAddress,
            ];

            if ($userId !== null) {
                $payload['user_id'] = $userId;
            }

            $response = $this->http()->post("{$this->baseUrl}/likes/toggle", $payload);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    public function getBatchLikes(array $items, ?string $ipAddress = null): array
    {
        if (empty($items)) {
            return [];
        }

        try {
            $response = $this->http()->post("{$this->baseUrl}/likes/batch", [
                'items' => $items,
                'ip_address' => $ipAddress,
            ]);

            if ($response->successful()) {
                return $response->json('data') ?? [];
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return [];
    }

    /**
     * @param  array<string>  $postUuids
     */
    public function getAuthorStats(int $userId, array $postUuids, string $period = 'all'): array
    {
        if (empty($postUuids)) {
            return ['total_views' => 0, 'unique_viewers' => 0, 'top_posts' => []];
        }

        try {
            $response = $this->http()->get("{$this->baseUrl}/authors/{$userId}/stats", [
                'post_uuids' => implode(',', $postUuids),
                'period' => $period,
            ]);

            if ($response->successful()) {
                return $response->json() ?? [];
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return ['total_views' => 0, 'unique_viewers' => 0, 'top_posts' => []];
    }
}
