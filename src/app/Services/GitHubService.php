<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GitHubService
{
    protected string $username;
    protected ?string $token;

    public function __construct()
    {
        $this->username = config('services.github.username', 'szymonborowski');
        $this->token    = config('services.github.token');
    }

    public function getRecentCommits(int $limit = 3): array
    {
        return Cache::remember('github.recent_commits', 3600, function () use ($limit) {
            $request = Http::withHeaders([
                'Accept'     => 'application/vnd.github+json',
                'User-Agent' => 'portfolio-app',
            ]);

            if ($this->token) {
                $request = $request->withToken($this->token);
            }

            $response = $request->get(
                "https://api.github.com/users/{$this->username}/events/public",
                ['per_page' => 30]
            );

            if (!$response->successful()) {
                return [];
            }

            $commits = [];

            foreach ($response->json() as $event) {
                if (($event['type'] ?? '') !== 'PushEvent') {
                    continue;
                }

                $repo     = $event['repo']['name'] ?? '';
                $repoUrl  = "https://github.com/{$repo}";
                $date     = $event['created_at'] ?? null;

                foreach (array_reverse($event['payload']['commits'] ?? []) as $commit) {
                    $sha     = $commit['sha'] ?? '';
                    $message = $commit['message'] ?? '';

                    $commits[] = [
                        'sha'     => $sha,
                        'short'   => substr($sha, 0, 7),
                        'message' => strtok($message, "\n"),
                        'repo'    => $repo,
                        'url'     => "{$repoUrl}/commit/{$sha}",
                        'date'    => $date,
                    ];

                    if (count($commits) >= $limit) {
                        return $commits;
                    }
                }
            }

            return $commits;
        });
    }

    public function getProfileUrl(): string
    {
        return "https://github.com/{$this->username}";
    }
}
