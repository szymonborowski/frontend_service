<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VoyageClient
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.voyage.api_key');
        $this->model   = config('services.voyage.model');
        $this->baseUrl = config('services.voyage.base_url');
    }

    /**
     * Embed a single text string. Returns a float array of 1024 dimensions.
     *
     * @return float[]
     */
    public function embed(string $text): array
    {
        return $this->embedBatch([$text])[0];
    }

    /**
     * Embed multiple texts in a single API call. Returns array of float arrays.
     *
     * @param  string[]  $texts
     * @return float[][]
     */
    public function embedBatch(array $texts): array
    {
        $response = Http::withToken($this->apiKey)
            ->post("{$this->baseUrl}/embeddings", [
                'model' => $this->model,
                'input' => $texts,
            ]);

        if ($response->failed()) {
            throw new \RuntimeException(
                'Voyage AI API error: ' . $response->status() . ' ' . $response->body()
            );
        }

        return array_column($response->json('data'), 'embedding');
    }
}
