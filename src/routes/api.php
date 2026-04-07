<?php

use App\Http\Controllers\Api\FormSubmissionController;
use App\Http\Middleware\VerifyInternalApiKey;
use Illuminate\Support\Facades\Route;

Route::middleware(VerifyInternalApiKey::class)->prefix('internal')->group(function () {
    Route::get('/form-submissions', [FormSubmissionController::class, 'index']);
    Route::get('/form-submissions/{id}', [FormSubmissionController::class, 'show']);
    Route::delete('/form-submissions/{id}', [FormSubmissionController::class, 'destroy']);
});
