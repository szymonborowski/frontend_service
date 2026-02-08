<?php

use App\Http\Controllers\CategoryViewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PostViewController;
use App\Http\Controllers\TagViewController;
use App\Http\Controllers\UserPanelController;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

// Kubernetes liveness probe - process is alive
Route::get('/health', function () {
    return response()->json(['status' => 'ok'], 200);
});

// Kubernetes readiness probe - Redis is reachable
Route::get('/ready', function () {
    try {
        Redis::connection()->ping();
        return response()->json(['status' => 'ready'], 200);
    } catch (\Throwable $e) {
        return response()->json(['status' => 'not ready', 'error' => $e->getMessage()], 503);
    }
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/kategoria/{slug}', [CategoryViewController::class, 'show'])->name('category.show');
Route::get('/tag/{slug}', [TagViewController::class, 'show'])->name('tag.show');
Route::get('/post/{slugOrId}', [PostViewController::class, 'show'])->name('post.show')->where('slugOrId', '[a-zA-Z0-9\-]+|\d+');

Route::get('/oauth/login', [OAuthController::class, 'login'])->name('login');
Route::get('/oauth/register', [OAuthController::class, 'register'])->name('register');
Route::get('/oauth/callback', [OAuthController::class, 'callback']);
Route::post('/oauth/logout', [OAuthController::class, 'logout'])->name('logout');

// Me (profile) routes – use MeController; legacy aliases redirect to panel
Route::get('/me', [MeController::class, 'show'])->name('me')->middleware('auth.session');
Route::put('/me/profile', [MeController::class, 'updateProfile'])->name('me.profile')->middleware('auth.session');
Route::put('/me/password', [MeController::class, 'updatePassword'])->name('me.password')->middleware('auth.session');

// User Panel Routes (protected)
Route::prefix('panel')->name('panel.')->middleware('auth.session')->group(function () {
    Route::get('/', fn() => redirect()->route('panel.posts'))->name('index');

    // User category
    Route::get('/profile', [UserPanelController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserPanelController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [UserPanelController::class, 'updatePassword'])->name('password.update');

    // Blog category
    Route::get('/posts', [UserPanelController::class, 'posts'])->name('posts');
    Route::get('/posts/create', [UserPanelController::class, 'createPost'])->name('posts.create');
    Route::post('/posts', [UserPanelController::class, 'storePost'])->name('posts.store');
    Route::get('/posts/{id}/edit', [UserPanelController::class, 'editPost'])->name('posts.edit');
    Route::put('/posts/{id}', [UserPanelController::class, 'updatePost'])->name('posts.update');
    Route::delete('/posts/{id}', [UserPanelController::class, 'deletePost'])->name('posts.delete');

    Route::get('/comments', [UserPanelController::class, 'comments'])->name('comments');
});
