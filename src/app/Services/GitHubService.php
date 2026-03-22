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
        $cached = Cache::get('github.recent_commits');
        if (\is_array($cached) && !empty($cached)) {
            return $cached;
        }

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

            $repo    = $event['repo']['name'] ?? '';
            $repoUrl = "https://github.com/{$repo}";
            $date    = $event['created_at'] ?? null;
            $sha     = $event['payload']['head'] ?? '';

            if (!$sha || !$repo) {
                continue;
            }

            // Payload commits may be stripped by the API; fetch commit details by SHA
            $payloadCommits = $event['payload']['commits'] ?? [];

            if (!empty($payloadCommits)) {
                foreach (array_reverse($payloadCommits) as $commit) {
                    $commitSha     = $commit['sha'] ?? '';
                    $commitMessage = $commit['message'] ?? '';

                    $commits[] = [
                        'sha'     => $commitSha,
                        'short'   => substr($commitSha, 0, 7),
                        'message' => explode("\n", $commitMessage)[0],
                        'repo'    => $repo,
                        'url'     => "{$repoUrl}/commit/{$commitSha}",
                        'date'    => $date,
                    ];

                    if (count($commits) >= $limit) {
                        break 2;
                    }
                }
            } else {
                $commitResponse = $request->get(
                    "https://api.github.com/repos/{$repo}/commits/{$sha}"
                );

                if (!$commitResponse->successful()) {
                    continue;
                }

                $commitData = $commitResponse->json();
                $message    = $commitData['commit']['message'] ?? '';

                $commits[] = [
                    'sha'     => $sha,
                    'short'   => substr($sha, 0, 7),
                    'message' => explode("\n", $message)[0],
                    'repo'    => $repo,
                    'url'     => "{$repoUrl}/commit/{$sha}",
                    'date'    => $date,
                ];

                if (count($commits) >= $limit) {
                    break;
                }
            }
        }

        if (!empty($commits)) {
            Cache::put('github.recent_commits', $commits, 3600);
        }

        return $commits;
    }

    public function getProfileUrl(): string
    {
        return "https://github.com/{$this->username}";
    }
}
