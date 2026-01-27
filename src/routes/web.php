<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/oauth/login', [OAuthController::class, 'redirect'])->name('login');
Route::get('/oauth/callback', [OAuthController::class, 'callback']);
Route::post('/oauth/logout', [OAuthController::class, 'logout'])->name('logout');
