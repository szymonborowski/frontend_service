<?php

namespace App\Http\Controllers;

use App\Services\BlogApiService;
use App\Services\UsersApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserPanelController extends Controller
{
    public function __construct(
        protected UsersApiService $usersApiService,
        protected BlogApiService $blogApiService
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
            'name' => ['required', 'string', 'max:255'],
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

    public function posts(): View
    {
        $user = session('user', []);
        $userId = $user['id'] ?? null;

        // TODO: Implement getUserPosts in BlogApiService
        $posts = $userId ? $this->blogApiService->getUserPosts($userId) : [];

        return view('panel.posts.index', [
            'posts' => $posts,
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
