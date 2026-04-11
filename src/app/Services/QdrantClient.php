<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class QdrantClient
{
    private string $baseUrl;
    private string $collection;

    public function __construct()
    {
        $host             = config('services.qdrant.host');
        $port             = config('services.qdrant.port');
        $this->baseUrl    = "http://{$host}:{$port}";
        $this->collection = config('services.qdrant.collection');
    }

    private function http(): PendingRequest
    {
        return Http::withHeaders(['Content-Type' => 'application/json']);
    }

    /**
     * Create the collection if it does not exist.
     */
    public function ensureCollection(): void
    {
        $response = $this->http()->get("{$this->baseUrl}/collections/{$this->collection}");

        if ($response->status() === 404) {
            $response = $this->http()->put("{$this->baseUrl}/collections/{$this->collection}", [
                'vectors' => [
                    'size'     => 1024,
                    'distance' => 'Cosine',
                ],
            ]);

            if ($response->failed()) {
                throw new \RuntimeException(
                    'Qdrant create collection error: ' . $response->status() . ' ' . $response->body()
                );
            }
        }
    }

    /**
     * Delete all points in the collection (used before full re-index).
     */
    public function clearCollection(): void
    {
        $this->http()->post("{$this->baseUrl}/collections/{$this->collection}/points/delete", [
            'filter' => new \stdClass(), // matches all points
        ]);
    }

    /**
     * Delete all points for a specific post slug.
     */
    public function deleteBySlug(string $slug): void
    {
        $this->http()->post("{$this->baseUrl}/collections/{$this->collection}/points/delete", [
            'filter' => [
                'must' => [
                    ['key' => 'slug', 'match' => ['value' => $slug]],
                ],
            ],
        ]);
    }

    /**
     * Upsert a batch of points.
     *
     * @param  array<array{id: string, vector: float[], payload: array}>  $points
     */
    public function upsertPoints(array $points): void
    {
        $response = $this->http()->put(
            "{$this->baseUrl}/collections/{$this->collection}/points",
            ['points' => $points]
        );

        if ($response->failed()) {
            throw new \RuntimeException(
                'Qdrant upsert error: ' . $response->status() . ' ' . $response->body()
            );
        }
    }

    /**
     * Search for the most similar points to the given vector.
     *
     * @param  float[]  $vector
     * @return array<array{score: float, payload: array}>
     */
    public function search(array $vector, int $limit = 5, float $scoreThreshold = 0.5): array
    {
        $response = $this->http()->post(
            "{$this->baseUrl}/collections/{$this->collection}/points/search",
            [
                'vector'          => $vector,
                'limit'           => $limit,
                'with_payload'    => true,
                'score_threshold' => $scoreThreshold,
            ]
        );

        if ($response->failed()) {
            throw new \RuntimeException(
                'Qdrant search error: ' . $response->status() . ' ' . $response->body()
            );
        }

        return $response->json('result') ?? [];
    }

    /**
     * Return the number of indexed points.
     */
    public function countPoints(): int
    {
        $response = $this->http()->get("{$this->baseUrl}/collections/{$this->collection}");

        return $response->json('result.points_count') ?? 0;
    }
}
