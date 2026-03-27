<?php

namespace App\Http\Controllers;

use App\Services\BlogApiService;
use App\Services\GitHubService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected BlogApiService $blogApi,
        protected GitHubService  $github,
    ) {}

    public function index(): View
    {
        $allPosts           = $this->blogApi->getRecentPosts(20);
        $mostImportantPosts = $this->blogApi->getMostImportantPosts();
        $categories         = $this->blogApi->getCategories();
        $tags               = $this->blogApi->getTags();
        $githubCommits      = $this->github->getRecentCommits(3);
        $githubProfileUrl   = $this->github->getProfileUrl();

        $isDevLog = fn (array $post): bool => collect($post['categories'] ?? [])
            ->contains(fn ($c) => ($c['slug'] ?? '') === 'dev-log');

        $recentArticles  = array_values(array_slice(array_filter($allPosts, fn ($p) => !$isDevLog($p)), 0, 6));
        $recentFeatures  = array_values(array_slice(array_filter($allPosts, $isDevLog), 0, 3));

        return view('home', [
            'recentArticles'     => $recentArticles,
            'recentFeatures'     => $recentFeatures,
            'mostImportantPosts' => $mostImportantPosts,
            'categories'         => $categories,
            'tags'               => $tags,
            'githubCommits'      => $githubCommits,
            'githubProfileUrl'   => $githubProfileUrl,
        ]);
    }
}
