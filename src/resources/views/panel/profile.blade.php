@extends('layouts.panel')

@section('title', __('panel.user_data_panel'))

@section('panel-title', __('panel.user_data'))

@section('panel-content')
    <div class="space-y-8">
        <!-- Profile data form -->
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('panel.profile_data') }}</h2>

            <form method="POST" action="{{ route('panel.profile.update') }}" class="space-y-6">
                @csrf
                @method('POST')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('auth.username') }}</label>
                    <input id="name" name="name" type="text" required autocomplete="name"
                           class="appearance-none rounded relative block w-full px-3 py-2 border @error('name') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-sky-700 dark:focus:ring-sky-500 focus:border-sky-700 dark:focus:border-sky-500 sm:text-sm"
                           value="{{ old('name', $user['name'] ?? '') }}">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('auth.email') }}</label>
                    <input id="email" name="email" type="email" required autocomplete="email"
                           class="appearance-none rounded relative block w-full px-3 py-2 border @error('email') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-sky-700 dark:focus:ring-sky-500 focus:border-sky-700 dark:focus:border-sky-500 sm:text-sm"
                           value="{{ old('email', $user['email'] ?? '') }}">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sky-800 hover:bg-sky-700 dark:bg-sky-700 dark:hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-sky-700">
                        {{ __('general.save_changes') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Account info (read-only) -->
        <div class="pt-8 border-t border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('panel.account_info') }}</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('panel.roles') }}</label>
                <div class="flex flex-wrap gap-2">
                    @forelse ($user['roles'] ?? [] as $role)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-sky-100 dark:bg-sky-900/30 text-sky-800 dark:text-sky-400">
                            {{ $role }}
                        </span>
                    @empty
                        <span class="text-sm text-gray-400 dark:text-gray-500">—</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Change password form -->
        <div class="pt-8 border-t border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('panel.change_password') }}</h2>

            <form method="POST" action="{{ route('panel.password.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('auth.current_password') }}</label>
                    <input id="current_password" name="current_password" type="password" required autocomplete="current-password"
                           class="appearance-none rounded relative block w-full px-3 py-2 border @error('current_password') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-sky-700 dark:focus:ring-sky-500 focus:border-sky-700 dark:focus:border-sky-500 sm:text-sm"
                           placeholder="{{ __('auth.enter_current_password') }}">
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('auth.new_password') }}</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="appearance-none rounded relative block w-full px-3 py-2 border @error('password') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-sky-700 dark:focus:ring-sky-500 focus:border-sky-700 dark:focus:border-sky-500 sm:text-sm"
                           placeholder="{{ __('auth.enter_new_password') }}">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('auth.confirm_password') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                           class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-sky-700 dark:focus:ring-sky-500 focus:border-sky-700 dark:focus:border-sky-500 sm:text-sm"
                           placeholder="{{ __('auth.repeat_new_password') }}">
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sky-800 hover:bg-sky-700 dark:bg-sky-700 dark:hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-sky-700">
                        {{ __('panel.change_password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
