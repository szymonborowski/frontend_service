<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('access_token')) {
            session(['redirect_after_login' => $request->url()]);
            return redirect()->route('login');
        }

        return $next($request);
    }
}
