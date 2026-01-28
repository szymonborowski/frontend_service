<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\OAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/oauth/login', [OAuthController::class, 'redirect'])->name('login');
Route::get('/oauth/callback', [OAuthController::class, 'callback']);
Route::post('/oauth/logout', [OAuthController::class, 'logout'])->name('logout');

Route::get('/me', [MeController::class, 'show'])->name('me');
Route::put('/me/profile', [MeController::class, 'updateProfile'])->name('me.profile');
Route::put('/me/password', [MeController::class, 'updatePassword'])->name('me.password');
