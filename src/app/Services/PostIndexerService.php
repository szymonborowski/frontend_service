<?php

namespace App\Services;

use Illuminate\Support\Str;

class PostIndexerService
{
    private const CHUNK_SIZE    = 1600; // ~400 tokens
    private const CHUNK_OVERLAP = 200;  // ~50 tokens
    private const BATCH_SIZE    = 50;   // embeddings per API call

    public function __construct(
        private VoyageClient $voyage,
        private QdrantClient $qdrant,
    ) {}

    /**
     * Re-index a single post by slug (incremental update).
     * Removes existing Qdrant points for the slug and re-indexes both locales.
     */
    public function indexBySlug(string $slug, BlogApiService $blogApi): void
    {
        $this->qdrant->ensureCollection();
        $this->qdrant->deleteBySlug($slug);

        $chunks = [];

        foreach (['en', 'pl'] as $locale) {
            $post = $blogApi->getPost($slug);

            if (!$post) {
                continue;
            }

            $post['locale'] = $locale;
            $text = $this->extractText($post);

            if (empty(trim($text))) {
                continue;
            }

            foreach ($this->chunk($text) as $i => $chunkText) {
                $chunks[] = [
                    'post_id'     => $post['id'] ?? 0,
                    'slug'        => $slug,
                    'title'       => $post['title'] ?? '',
                    'locale'      => $locale,
                    'chunk_index' => $i,
                    'content'     => $chunkText,
                    'categories'  => array_column($post['categories'] ?? [], 'name'),
                    'tags'        => array_column($post['tags'] ?? [], 'name'),
                ];
            }
        }

        if (!empty($chunks)) {
            $this->embedAndUpsert($chunks);
        }
    }

    /**
     * Index all provided posts into Qdrant.
     * Returns [postsIndexed, chunksIndexed].
     *
     * @param  array[]  $posts
     * @return array{0: int, 1: int}
     */
    public function index(array $posts): array
    {
        $this->qdrant->ensureCollection();
        $this->qdrant->clearCollection();

        $chunks = [];

        foreach ($posts as $post) {
            $text = $this->extractText($post);

            if (empty(trim($text))) {
                continue;
            }

            foreach ($this->chunk($text) as $i => $chunkText) {
                $chunks[] = [
                    'post_id'     => $post['id'] ?? 0,
                    'slug'        => $post['slug'] ?? '',
                    'title'       => $post['title'] ?? '',
                    'locale'      => $post['locale'] ?? 'en',
                    'chunk_index' => $i,
                    'content'     => $chunkText,
                    'categories'  => array_column($post['categories'] ?? [], 'name'),
                    'tags'        => array_column($post['tags'] ?? [], 'name'),
                ];
            }
        }

        if (empty($chunks)) {
            return [0, 0];
        }

        $this->embedAndUpsert($chunks);

        return [count($posts), count($chunks)];
    }

    private function extractText(array $post): string
    {
        $parts = [];

        if (!empty($post['title'])) {
            $parts[] = $post['title'];
        }

        if (!empty($post['excerpt'])) {
            $parts[] = strip_tags($post['excerpt']);
        }

        if (!empty($post['content'])) {
            $parts[] = strip_tags($post['content']);
        }

        return implode("\n\n", $parts);
    }

    /**
     * Split text into overlapping chunks.
     *
     * @return string[]
     */
    private function chunk(string $text): array
    {
        $chunks = [];
        $length = mb_strlen($text);
        $offset = 0;

        while ($offset < $length) {
            $chunk = mb_substr($text, $offset, self::CHUNK_SIZE);

            if (mb_strlen($chunk) < 50) {
                break; // skip tiny trailing fragments
            }

            $chunks[] = $chunk;
            $offset  += self::CHUNK_SIZE - self::CHUNK_OVERLAP;
        }

        return $chunks;
    }

    /**
     * Embed chunks in batches and upsert into Qdrant.
     */
    private function embedAndUpsert(array $chunks): void
    {
        foreach (array_chunk($chunks, self::BATCH_SIZE) as $batch) {
            $texts   = array_column($batch, 'content');
            $vectors = $this->voyage->embedBatch($texts);

            $points = [];
            foreach ($batch as $i => $chunk) {
                $points[] = [
                    'id'      => (string) Str::uuid(),
                    'vector'  => $vectors[$i],
                    'payload' => $chunk,
                ];
            }

            $this->qdrant->upsertPoints($points);
        }
    }
}
