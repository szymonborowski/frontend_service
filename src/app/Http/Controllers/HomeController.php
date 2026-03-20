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
        $recentPosts        = $this->blogApi->getRecentPosts(10);
        $mostImportantPosts = $this->blogApi->getMostImportantPosts();
        $categories         = $this->blogApi->getCategories();
        $tags               = $this->blogApi->getTags();
        $githubCommits      = $this->github->getRecentCommits(3);
        $githubProfileUrl   = $this->github->getProfileUrl();

        return view('home', [
            'recentPosts'        => $recentPosts,
            'mostImportantPosts' => $mostImportantPosts,
            'categories'         => $categories,
            'tags'               => $tags,
            'githubCommits'      => $githubCommits,
            'githubProfileUrl'   => $githubProfileUrl,
        ]);
    }
}
