@extends('layouts.app')

@section('title', 'Moj profil')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Moj profil</h1>

        <div class="space-y-8">
            <!-- Formularz edycji danych profilu -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Dane profilu</h2>

                @if(session('profile_success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('profile_success') }}
                    </div>
                @endif

                @error('profile')
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ $message }}
                    </div>
                @enderror

                <form method="POST" action="{{ route('me.profile') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nazwa uzytkownika</label>
                        <input id="name" name="name" type="text" required autocomplete="name"
                               class="appearance-none rounded relative block w-full px-3 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-sky-700 focus:border-sky-700 sm:text-sm"
                               value="{{ old('name', $user['name'] ?? '') }}">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" name="email" type="email" required autocomplete="email"
                               class="appearance-none rounded relative block w-full px-3 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-sky-700 focus:border-sky-700 sm:text-sm"
                               value="{{ old('email', $user['email'] ?? '') }}">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sky-800 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-700">
                            Zapisz zmiany
                        </button>
                    </div>
                </form>
            </div>

            <!-- Formularz zmiany hasla -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Zmiana hasla</h2>

                @if(session('password_success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('password_success') }}
                    </div>
                @endif

                @error('password')
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ $message }}
                    </div>
                @enderror

                <form method="POST" action="{{ route('me.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Obecne haslo</label>
                        <input id="current_password" name="current_password" type="password" required autocomplete="current-password"
                               class="appearance-none rounded relative block w-full px-3 py-2 border @error('current_password') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-sky-700 focus:border-sky-700 sm:text-sm"
                               placeholder="Wprowadz obecne haslo">
                        @error('current_password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nowe haslo</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                               class="appearance-none rounded relative block w-full px-3 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-sky-700 focus:border-sky-700 sm:text-sm"
                               placeholder="Wprowadz nowe haslo (min. 8 znakow)">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Potwierdz nowe haslo</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                               class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-sky-700 focus:border-sky-700 sm:text-sm"
                               placeholder="Powtorz nowe haslo">
                    </div>

                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sky-800 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-700">
                            Zmien haslo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
