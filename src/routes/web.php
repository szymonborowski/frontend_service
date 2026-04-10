<?php

use App\Http\Controllers\CategoryViewController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PostViewController;
use App\Http\Controllers\TagViewController;
use App\Http\Controllers\UserPanelController;
use App\Mail\NewsletterWelcome;
use Illuminate\Support\Facades\Mail;
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

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['pl', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about',         fn() => view('about'))->name('about');
Route::get('/contact',       fn() => view('contact'))->name('contact');
Route::post('/contact',      [ContactController::class, 'send'])->middleware('throttle:5,1')->name('contact.send');
Route::get('/collaboration', fn() => view('collaboration'))->name('collaboration');

// AI Chat
Route::post('/chat/send',  [ChatController::class, 'send'])->middleware('throttle:chat')->name('chat.send');
Route::post('/chat/clear', [ChatController::class, 'clear'])->middleware('throttle:30,1')->name('chat.clear');

Route::get('/kategoria/{slug}', [CategoryViewController::class, 'show'])->name('category.show');
Route::get('/tag/{slug}', [TagViewController::class, 'show'])->name('tag.show');
Route::get('/post/{slugOrId}', [PostViewController::class, 'show'])->name('post.show')->where('slugOrId', '[a-zA-Z0-9\-]+|\d+');
Route::post('/post/{postId}/comments', [CommentController::class, 'store'])->name('post.comments.store')->middleware('auth.session')->where('postId', '\d+');

// Newsletter
Route::post('/newsletter/subscribe', function (\Illuminate\Http\Request $request) {
    if (!env('NEWSLETTER_ENABLED', false)) {
        return response()->json(['success' => false, 'message' => 'Newsletter is disabled.'], 503);
    }

    $email = $request->input('email', '');
    $result = app(\App\Services\BlogApiService::class)->subscribeNewsletter($email);

    if ($result['success'] && $email) {
        Mail::to($email)->queue(new NewsletterWelcome($email));
    }

    return response()->json($result);
})->middleware('throttle:5,1')->name('newsletter.subscribe');

// Likes
Route::post('/likes/toggle', function (\Illuminate\Http\Request $request) {
    $userId = session('user.id') ? (int) session('user.id') : null;

    $result = app(\App\Services\AnalyticsApiService::class)->toggleLike(
        $request->input('type', ''),
        $request->input('id', ''),
        $request->ip(),
        $userId,
    );

    if ($result === null) {
        return response()->json(['error' => 'Service unavailable'], 503);
    }

    return response()->json($result);
})->middleware('throttle:30,1')->name('likes.toggle');

Route::get('/oauth/login', [OAuthController::class, 'login'])->name('login');
Route::get('/oauth/register', [OAuthController::class, 'register'])->name('register');
Route::get('/oauth/callback', [OAuthController::class, 'callback']);
Route::post('/oauth/logout', [OAuthController::class, 'logout'])->name('logout');

// User Panel Routes (protected)
Route::prefix('panel')->name('panel.')->middleware('auth.session')->group(function () {
    Route::get('/', fn() => redirect()->route('panel.posts'))->name('index');

    // User category
    Route::get('/profile', [UserPanelController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserPanelController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [UserPanelController::class, 'updatePassword'])->name('password.update');

    // Blog category
    Route::get('/posts', [UserPanelController::class, 'posts'])->name('posts');
    Route::get('/posts/create', [UserPanelController::class, 'createPost'])->name('posts.create');
    Route::post('/posts', [UserPanelController::class, 'storePost'])->name('posts.store');
    Route::get('/posts/{id}/edit', [UserPanelController::class, 'editPost'])->name('posts.edit');
    Route::put('/posts/{id}', [UserPanelController::class, 'updatePost'])->name('posts.update');
    Route::delete('/posts/{id}', [UserPanelController::class, 'deletePost'])->name('posts.delete');

    Route::get('/comments', [UserPanelController::class, 'comments'])->name('comments');
    Route::get('/analytics', [UserPanelController::class, 'analytics'])->name('analytics');

    // Media picker API (JSON)
    Route::get('/media', [UserPanelController::class, 'mediaJson'])->name('media.json');
});
