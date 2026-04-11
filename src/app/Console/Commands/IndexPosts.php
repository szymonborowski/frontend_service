<?php

namespace App\Console\Commands;

use App\Services\BlogApiService;
use App\Services\PostIndexerService;
use Illuminate\Console\Command;

class IndexPosts extends Command
{
    protected $signature = 'chat:index-posts
                            {--locale= : Index a specific locale (en or pl). Defaults to both.}
                            {--fresh : Drop and recreate the collection before indexing.}';

    protected $description = 'Index all published blog posts into Qdrant for semantic search';

    public function handle(BlogApiService $blogApi, PostIndexerService $indexer): int
    {
        $localeOption = $this->option('locale');
        $locales      = $localeOption ? [$localeOption] : ['en', 'pl'];

        $totalPosts  = 0;
        $totalChunks = 0;

        foreach ($locales as $locale) {
            $this->info("Fetching posts [{$locale}]...");
            $posts = $blogApi->getAllPostsForIndexing($locale);
            $this->line('  Found ' . count($posts) . ' posts.');

            if (empty($posts)) {
                continue;
            }

            $this->info("Indexing [{$locale}]...");
            [$posts, $chunks] = $indexer->index($posts);

            $totalPosts  += $posts;
            $totalChunks += $chunks;

            $this->line("  Indexed {$posts} posts → {$chunks} chunks.");
        }

        $this->newLine();
        $this->info("Done. Total: {$totalPosts} posts, {$totalChunks} chunks.");

        return Command::SUCCESS;
    }
}
