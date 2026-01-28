<?php

namespace App\Http\Controllers;

use App\Services\UsersApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MeController extends Controller
{
    public function __construct(
        protected UsersApiService $usersApiService
    ) {}

    public function show(): View|RedirectResponse
    {
        if (!session('access_token')) {
            return redirect()->route('login');
        }

        $user = session('user', []);

        return view('me', [
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        if (!session('access_token')) {
            return redirect()->route('login');
        }

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

            return back()->with('profile_success', 'Dane profilu zostaly zaktualizowane.');
        }

        return back()->withErrors(['profile' => 'Nie udalo sie zaktualizowac danych profilu.']);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        if (!session('access_token')) {
            return redirect()->route('login');
        }

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
            return back()->with('password_success', 'Haslo zostalo zmienione.');
        }

        return back()->withErrors(['password' => 'Nie udalo sie zmienic hasla.']);
    }
}
