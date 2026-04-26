<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    private const SUPPORTED = ['pl', 'en'];

    public function handle(Request $request, Closure $next): mixed
    {
        $locale = $this->resolve($request);

        app()->setLocale($locale);

        if ($request->query('lang') === $locale) {
            session(['locale' => $locale]);
        }

        return $next($request);
    }

    private function resolve(Request $request): string
    {
        $query = $request->query('lang');
        if (is_string($query) && in_array($query, self::SUPPORTED, true)) {
            return $query;
        }

        $session = session('locale');
        if (is_string($session) && in_array($session, self::SUPPORTED, true)) {
            return $session;
        }

        foreach ($request->getLanguages() as $lang) {
            $primary = strtolower(substr((string) $lang, 0, 2));
            if (in_array($primary, self::SUPPORTED, true)) {
                return $primary;
            }
        }

        $config = config('app.locale');
        return in_array($config, self::SUPPORTED, true) ? $config : 'en';
    }
}
