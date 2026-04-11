<?php

namespace App\Services;

class BlogEventsMessageHandler
{
    public function __construct(
        private PostIndexerService $indexer,
        private BlogApiService $blogApi,
    ) {}

    public function handle(string $body): void
    {
        $data   = json_decode($body, true);
        $action = $data['action'] ?? '';
        $slug   = $data['post']['slug'] ?? '';

        if (empty($slug)) {
            return;
        }

        match ($action) {
            'published' => $this->indexer->indexBySlug($slug, $this->blogApi),
            default     => null,
        };
    }
}
