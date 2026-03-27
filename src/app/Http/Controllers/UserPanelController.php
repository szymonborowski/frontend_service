<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsApiService;
use App\Services\BlogApiService;
use App\Services\UsersApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserPanelController extends Controller
{
    public function __construct(
        protected UsersApiService $usersApiService,
        protected BlogApiService $blogApiService,
        protected AnalyticsApiService $analyticsApiService,
    ) {}

    // === User Category ===

    public function profile(): View
    {
        return view('panel.profile', [
            'user' => session('user', []),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:32', 'regex:/^[a-zA-Z0-9_-]+$/'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user = session('user', []);
        $userId = $user['id'] ?? null;

        if (!$userId) {
            return back()->withErrors(['profile' => 'Nie mozna zidentyfikowac uzytkownika.']);
        }

        $result = $this->usersApiService->updateUser($userId, $validated);

        if ($result['success']) {
            $user['name'] = $validated['name'];
            $user['email'] = $validated['email'];
            session(['user' => $user]);

            return back()->with('success', 'Dane profilu zostaly zaktualizowane.');
        }

        return back()->withErrors(['profile' => 'Nie udalo sie zaktualizowac danych profilu.']);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = session('user', []);
        $userId = $user['id'] ?? null;
        $userEmail = $user['email'] ?? null;

        if (!$userId || !$userEmail) {
            return back()->withErrors(['password' => 'Nie mozna zidentyfikowac uzytkownika.']);
        }

        $isPasswordValid = $this->usersApiService->verifyPassword(
            $userEmail,
            $validated['current_password']
        );

        if (!$isPasswordValid) {
            return back()->withErrors(['current_password' => 'Obecne haslo jest nieprawidlowe.']);
        }

        $result = $this->usersApiService->updatePassword($userId, $validated['password']);

        if ($result['success']) {
            return back()->with('success', 'Haslo zostalo zmienione.');
        }

        return back()->withErrors(['password' => 'Nie udalo sie zmienic hasla.']);
    }

    // === Blog Category ===

    public function posts(Request $request): View
    {
        $user = session('user', []);
        $userId = $user['id'] ?? null;

        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 15);

        $posts = $userId ? $this->blogApiService->getUserPosts($userId, $page, $perPage) : [];

        // Fetch view counts for all posts in one request
        $postUuids = array_filter(array_column($posts['data'] ?? [], 'uuid'));
        $viewsByUuid = [];

        if ($userId && !empty($postUuids)) {
            $stats = $this->analyticsApiService->getAuthorStats($userId, array_values($postUuids), 'all');
            foreach ($stats['top_posts'] ?? [] as $topPost) {
                $viewsByUuid[$topPost['post_uuid']] = $topPost['views'];
            }
        }

        return view('panel.posts.index', [
            'posts' => $posts,
            'viewsByUuid' => $viewsByUuid,
            'meta' => $posts['meta'] ?? [],
            'currentPerPage' => $perPage,
        ]);
    }

    public function analytics(): View
    {
        $user = session('user', []);
        $userId = $user['id'] ?? null;

        $posts = $userId ? $this->blogApiService->getUserPosts($userId, page: 1) : [];
        $postList = $posts['data'] ?? [];
        $postUuids = array_filter(array_column($postList, 'uuid'));

        $stats = ['total_views' => 0, 'unique_viewers' => 0, 'top_posts' => []];
        $viewsByUuid = [];

        if ($userId && !empty($postUuids)) {
            $stats = $this->analyticsApiService->getAuthorStats($userId, array_values($postUuids), 'all');
            foreach ($stats['top_posts'] ?? [] as $topPost) {
                $viewsByUuid[$topPost['post_uuid']] = $topPost['views'];
            }
        }

        // Merge view counts into post list and sort by views desc
        $postList = array_map(function ($post) use ($viewsByUuid) {
            $post['views'] = $viewsByUuid[$post['uuid'] ?? ''] ?? 0;
            return $post;
        }, $postList);

        usort($postList, fn ($a, $b) => $b['views'] <=> $a['views']);

        return view('panel.analytics', [
            'postList' => $postList,
            'totalViews' => $stats['total_views'] ?? 0,
            'uniqueViewers' => $stats['unique_viewers'] ?? 0,
        ]);
    }

    public function createPost(): View
    {
        $categories = $this->blogApiService->getCategories();
        $tags = $this->blogApiService->getTags();

        return view('panel.posts.create', [
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    public function storePost(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'categories' => ['nullable', 'array'],
            'tags' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $user = session('user', []);
        $validated['author_id'] = $user['id'] ?? null;

        // TODO: Implement createPost in BlogApiService
        $result = $this->blogApiService->createPost($validated);

        if ($result['success'] ?? false) {
            return redirect()->route('panel.posts')->with('success', 'Post zostal utworzony.');
        }

        return back()->withInput()->withErrors(['post' => 'Nie udalo sie utworzyc posta.']);
    }

    public function editPost(int $id): View
    {
        // TODO: Implement getPostById in BlogApiService
        $post = $this->blogApiService->getPostById($id);
        $categories = $this->blogApiService->getCategories();
        $tags = $this->blogApiService->getTags();

        if (!$post) {
            abort(404);
        }

        return view('panel.posts.edit', [
            'post' => $post,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    public function updatePost(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'slug' => ['nullable', 'string', 'max:255'],
            'categories' => ['nullable', 'array'],
            'tags' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
        ]);

        // TODO: Implement updatePost in BlogApiService
        $result = $this->blogApiService->updatePost($id, $validated);

        if ($result['success'] ?? false) {
            return redirect()->route('panel.posts')->with('success', 'Post zostal zaktualizowany.');
        }

        return back()->withInput()->withErrors(['post' => 'Nie udalo sie zaktualizowac posta.']);
    }

    public function deletePost(int $id): RedirectResponse
    {
        // TODO: Implement deletePost in BlogApiService
        $result = $this->blogApiService->deletePost($id);

        if ($result['success'] ?? false) {
            return redirect()->route('panel.posts')->with('success', 'Post zostal usuniety.');
        }

        return back()->withErrors(['post' => 'Nie udalo sie usunac posta.']);
    }

    public function comments(): View
    {
        $user = session('user', []);
        $userId = $user['id'] ?? null;

        // TODO: Implement getUserComments in BlogApiService
        $comments = $userId ? $this->blogApiService->getUserComments($userId) : [];

        return view('panel.comments', [
            'comments' => $comments,
        ]);
    }
}
