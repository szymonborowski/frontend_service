<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Extended\Mind::Thesis()')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">
    <header
        x-data="{ scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 60"
        class="fixed top-0 left-0 right-0 z-50 bg-white transition-all duration-300 ease-in-out"
        :class="scrolled ? 'h-16 shadow-md' : 'h-28 shadow-sm'"
    >
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between"
        >
            {{-- Avatar + tytuł --}}
            <div class="flex items-center" :class="scrolled ? 'gap-3' : 'gap-5'">
                <a href="{{ url('/') }}">
                    <img
                        src="/images/me200x200.png"
                        alt="Szymon Borowski"
                        class="rounded-full object-cover shrink-0 transition-all duration-300 hover:opacity-80"
                        :class="scrolled ? 'w-9 h-9' : 'w-20 h-20'"
                    >
                </a>
                <div class="relative group">
                    <a
                        href="{{ url('/') }}"
                        class="font-mono font-bold text-gray-900 hover:text-sky-800 whitespace-nowrap transition-all duration-300 cursor-help"
                        :class="scrolled ? 'text-base' : 'text-xl'"
                    >
                        <span class="text-gray-400">Extended\</span><span>Mind</span><span class="text-sky-700">::</span><span>Thesis</span><span class="text-gray-400">()</span>
                    </a>
                    <div class="pointer-events-none absolute left-0 top-full mt-2 w-80 rounded-md bg-gray-900 px-3 py-2 text-xs text-gray-200 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50 shadow-lg">
                        {{ __('general.blog_tooltip') }}
                    </div>
                </div>
            </div>

            {{-- Nav --}}
            <div class="flex items-center space-x-4">
                @if(session('access_token'))
                    @php $user = session('user', []); @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 hover:opacity-80 focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden shrink-0 mr-1">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 ml-2">{{ $user['name'] ?? __('general.user') }}</span>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="{{ route('panel.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ __('general.my_profile') }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        {{ __('general.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-sky-800">
                        {{ __('general.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sky-800 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-700">
                        {{ __('general.register') }}
                    </a>
                @endif
            </div>
        </div>
    </header>

    <main class="pt-28">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- Główna sekcja --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                {{-- Dane teleadresowe --}}
                <div>
                    <h3 class="font-mono font-semibold text-gray-900 text-sm mb-4">
                        <span class="text-gray-400">Extended\</span>Mind<span class="text-sky-700">::</span>Contact<span class="text-gray-400">()</span>
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Szymon Borowski
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Kraków, Polska
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:kontakt@borowski.dev" class="hover:text-sky-700 transition-colors">kontakt@borowski.dev</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                            </svg>
                            <a href="https://borowski.dev" target="_blank" rel="noopener" class="hover:text-sky-700 transition-colors">borowski.dev</a>
                        </li>
                    </ul>
                </div>

                {{-- Kolumna 1: Nawigacja --}}
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm mb-4 uppercase tracking-wide">Nawigacja</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="{{ url('/') }}" class="hover:text-sky-700 transition-colors">Blog</a></li>
                        <li><a href="{{ url('/about') }}" class="hover:text-sky-700 transition-colors">O mnie</a></li>
                        <li><a href="{{ url('/contact') }}" class="hover:text-sky-700 transition-colors">Kontakt</a></li>
                        <li>
                            <a href="/rss.xml" class="flex items-center gap-1.5 hover:text-sky-700 transition-colors">
                                <svg class="w-3.5 h-3.5 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M6.18 15.64a2.18 2.18 0 010 4.36 2.18 2.18 0 010-4.36M4 4.44A15.56 15.56 0 0119.56 20h-2.83A12.73 12.73 0 004 7.27V4.44m0 5.66a9.9 9.9 0 019.9 9.9h-2.83A7.07 7.07 0 004 12.93V10.1z"/>
                                </svg>
                                RSS
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Kolumna 2: Social / Dev --}}
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm mb-4 uppercase tracking-wide">Znajdź mnie</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>
                            <a href="https://github.com/szymonborowski" target="_blank" rel="noopener" class="flex items-center gap-2 hover:text-sky-700 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                                </svg>
                                GitHub
                            </a>
                        </li>
                        <li>
                            <a href="https://linkedin.com/in/szymonborowski" target="_blank" rel="noopener" class="flex items-center gap-2 hover:text-sky-700 transition-colors">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                                LinkedIn
                            </a>
                        </li>
                        <li>
                            <a href="/cv.pdf" target="_blank" class="flex items-center gap-2 hover:text-sky-700 transition-colors">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Pobierz CV
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Dolny pasek --}}
            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-400">
                <p>&copy; {{ date('Y') }} Szymon Borowski. {{ __('general.all_rights_reserved') }}</p>
                <p class="font-mono">Built with Laravel &amp; Livewire</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
