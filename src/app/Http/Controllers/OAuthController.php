<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    public function redirect(Request $request)
    {
        $state = Str::random(40);
        $request->session()->put('oauth_state', $state);

        $query = http_build_query([
            'client_id' => config('services.sso.client_id'),
            'redirect_uri' => config('services.sso.redirect_uri'),
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);

        return redirect(config('services.sso.url') . '/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        $storedState = $request->session()->pull('oauth_state');

        if (!$storedState || $storedState !== $request->input('state')) {
            abort(400, 'Invalid state parameter');
        }

        if ($request->has('error')) {
            abort(400, $request->input('error_description', 'Authorization failed'));
        }

        $response = Http::asForm()->post(config('services.sso.internal_url') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.sso.client_id'),
            'client_secret' => config('services.sso.client_secret'),
            'redirect_uri' => config('services.sso.redirect_uri'),
            'code' => $request->input('code'),
        ]);

        if ($response->failed()) {
            abort(400, 'Token exchange failed');
        }

        $tokens = $response->json();
        $accessToken = $tokens['access_token'];

        $request->session()->put('access_token', $accessToken);

        if (isset($tokens['refresh_token'])) {
            $request->session()->put('refresh_token', $tokens['refresh_token']);
        }

        // Decode JWT payload to get user data
        $parts = explode('.', $accessToken);
        if (count($parts) === 3) {
            $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
            if ($payload) {
                $request->session()->put('user', [
                    'id' => $payload['sub'] ?? null,
                    'name' => $payload['name'] ?? null,
                    'email' => $payload['email'] ?? null,
                ]);
            }
        }

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['access_token', 'refresh_token', 'user']);

        $redirectUri = urlencode(route('home'));

        return redirect(config('services.sso.url') . '/logout?redirect_uri=' . $redirectUri);
    }
}
