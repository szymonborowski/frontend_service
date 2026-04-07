<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyInternalApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = config('services.internal.api_key');

        if (!$apiKey || $request->header('X-Internal-Api-Key') !== $apiKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
