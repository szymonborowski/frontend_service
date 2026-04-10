<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class AnthropicClient
{
    private string $apiKey;
    private string $model;
    private int $maxTokens;
    private string $baseUrl = 'https://api.anthropic.com/v1';

    public function __construct()
    {
        $this->apiKey    = config('services.anthropic.api_key');
        $this->model     = config('services.anthropic.model');
        $this->maxTokens = config('services.anthropic.max_tokens');
    }

    /**
     * Send a message to the Anthropic Messages API.
     *
     * @param  string  $systemPrompt
     * @param  array   $messages  [['role' => 'user'|'assistant', 'content' => string], ...]
     * @return string  The assistant's reply text
     * @throws \RuntimeException
     */
    public function sendMessage(string $systemPrompt, array $messages): string
    {
        $response = Http::withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->post("{$this->baseUrl}/messages", [
            'model'      => $this->model,
            'max_tokens' => $this->maxTokens,
            'system'     => $systemPrompt,
            'messages'   => $messages,
        ]);

        if ($response->failed()) {
            throw new \RuntimeException(
                'Anthropic API error: ' . $response->status() . ' ' . $response->body()
            );
        }

        return $response->json('content.0.text', '');
    }
}
