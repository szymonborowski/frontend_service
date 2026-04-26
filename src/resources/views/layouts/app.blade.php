<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Extended\Mind::Thesis()')</title>

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', 'Extended\Mind::Thesis()')">
    <meta property="og:description" content="@yield('og_description', 'Blog Szymona Borowskiego — AI Engineer i Laravel developer. Anthropic API, RAG, event-driven microservices, Kubernetes, observability.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', url('/images/og-cover.png'))">
    <meta property="og:site_name" content="Extended\Mind::Thesis()">
    <meta property="og:locale" content="{{ app()->getLocale() == 'pl' ? 'pl_PL' : 'en_US' }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Extended\Mind::Thesis()')">
    <meta name="twitter:description" content="@yield('og_description', 'Blog Szymona Borowskiego — AI Engineer i Laravel developer. Anthropic API, RAG, event-driven microservices, Kubernetes, observability.')">
    <meta name="twitter:image" content="@yield('og_image', url('/images/og-cover.png'))">

    {{-- General meta --}}
    <meta name="description" content="@yield('og_description', 'Blog Szymona Borowskiego — AI Engineer i Laravel developer. Anthropic API, RAG, event-driven microservices, Kubernetes, observability.')">

    {{-- Canonical + hreflang alternates --}}
    @php
        $cleanQuery = collect(request()->query())->except('lang')->all();
        $canonicalUrl = url(request()->path()) . (empty($cleanQuery) ? '' : '?' . http_build_query($cleanQuery));
        $altEn = url(request()->path()) . '?' . http_build_query(array_merge($cleanQuery, ['lang' => 'en']));
        $altPl = url(request()->path()) . '?' . http_build_query(array_merge($cleanQuery, ['lang' => 'pl']));
    @endphp
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $altEn }}">
    <link rel="alternate" hreflang="pl" href="{{ $altPl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}">

    <link rel="icon" href="/favicon_1.ico" sizes="32x32" type="image/x-icon">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    {{-- Anti-flash: set dark class before paint --}}
    <script>
        (function(){
            var t = localStorage.getItem('theme');
            if (t === 'light' ? false : (t === 'auto' ? window.matchMedia('(prefers-color-scheme: dark)').matches : true)) {
                document.documentElement.classList.add('dark');
            }
        })();
        // Scroll-triggered fade-in (no Alpine dependency)
        function fadeInOnScroll(el) {
            new IntersectionObserver(function(entries, obs) {
                entries.forEach(function(e) {
                    if (e.isIntersecting) { e.target.classList.add('animate-fade-in-up'); obs.unobserve(e.target); }
                });
            }, { threshold: 0.1 }).observe(el);
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-100 dark:bg-gray-950 min-h-screen text-gray-800 dark:text-gray-100 transition-colors duration-200">
    <header
        x-data="{ scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 60"
        class="fixed top-0 left-0 right-0 z-50 bg-gray-50/80 dark:bg-gray-900/80 backdrop-blur-lg transition-all duration-300 ease-in-out border-b border-gray-200/50 dark:border-gray-700/50"
        :class="scrolled ? 'h-16 shadow-md' : 'h-28 shadow-sm'"
    >
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between"
        >
            {{-- Avatar + title --}}
            <div class="flex items-center" :class="scrolled ? 'gap-3' : 'gap-5'">
                <a href="{{ url('/') }}">
                    <img
                        src="/images/me200x200.png"
                        alt="Szymon Borowski"
                        class="rounded-full object-cover shrink-0 transition-all duration-300 hover:opacity-80 ring-2 ring-gray-300 dark:ring-gray-700"
                        :class="scrolled ? 'w-9 h-9' : 'w-20 h-20'"
                    >
                </a>
                <div class="relative group">
                    <a
                        href="{{ url('/') }}"
                        class="font-mono font-bold text-gray-800 dark:text-gray-100 hover:text-sky-700 dark:hover:text-sky-400 whitespace-nowrap transition-all duration-300 cursor-help"
                        :class="scrolled ? 'text-base' : 'text-xl'"
                    >
                        <span class="text-gray-400 dark:text-gray-500">Extended\</span><span>Mind</span><span class="text-sky-700 dark:text-sky-400">::</span><span>Thesis</span><span class="text-gray-400 dark:text-gray-500">()</span>
                    </a>
                    <div class="pointer-events-none absolute left-0 top-full mt-2 w-80 rounded-md bg-gray-900 dark:bg-gray-700 px-3 py-2 text-xs text-gray-200 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50 shadow-lg">
                        {{ __('general.blog_tooltip') }}
                    </div>
                </div>
            </div>

            {{-- Nav --}}
            <div class="flex items-center space-x-3">
                <livewire:search-box />
                <x-language-toggle />
                <x-theme-toggle />

                @if(session('access_token'))
                    @php $user = session('user', []); @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 hover:opacity-80 focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden shrink-0 mr-1">
                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-2">{{ $user['name'] ?? __('general.user') }}</span>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black/5 dark:ring-white/10 z-50">
                            <div class="py-1">
                                <a href="{{ route('panel.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ __('general.my_profile') }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <svg class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        {{ __('general.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-sky-700 dark:hover:text-sky-400">
                        {{ __('general.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sky-700 hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-700 dark:focus:ring-offset-gray-900">
                        {{ __('general.register') }}
                    </a>
                @endif
            </div>
        </div>
    </header>

    <main class="pt-28">
        @yield('content')
    </main>

    @yield('pre-footer')

    <footer class="bg-gray-100 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- Main grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-16 gap-y-10">

                {{-- Contact info --}}
                <div>
                    <h3 class="font-mono font-semibold text-gray-800 dark:text-gray-100 text-sm mb-4">
                        <span class="text-gray-400 dark:text-gray-500">Extended\</span>Mind<span class="text-sky-700 dark:text-sky-400">::</span>Contact<span class="text-gray-400 dark:text-gray-500">()</span>
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-700 dark:text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Szymon Borowski
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-700 dark:text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Koszalin, Polska
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-700 dark:text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:szymon.borowski@gmail.com" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors">szymon.borowski@gmail.com</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-700 dark:text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <a href="tel:+48509132087" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors">+48 509 132 087</a>
                        </li>
                    </ul>
                </div>

                {{-- Navigation --}}
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 text-sm mb-4 uppercase tracking-wide">{{ __('general.footer_navigation') }}</h3>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li><a href="{{ url('/') }}" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors">{{ __('general.footer_blog') }}</a></li>
                        <li><a href="{{ url('/about') }}" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors">{{ __('general.footer_about') }}</a></li>
                        <li><a href="{{ url('/contact') }}" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors">{{ __('general.footer_contact') }}</a></li>
                        <li><a href="{{ url('/collaboration') }}" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors">{{ __('general.footer_collaboration') }}</a></li>
                    </ul>
                </div>

                {{-- Social / Dev --}}
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 text-sm mb-4 uppercase tracking-wide">{{ __('general.footer_find_me') }}</h3>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li>
                            <a href="https://github.com/szymonborowski" target="_blank" rel="noopener" class="flex items-center gap-2 hover:text-sky-700 dark:hover:text-sky-400 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                                </svg>
                                GitHub
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/in/szymon-borowski-db84/" target="_blank" rel="noopener" class="flex items-center gap-2 hover:text-sky-700 dark:hover:text-sky-400 transition-colors">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                                LinkedIn
                            </a>
                        </li>
                        <li>
                            <a href="{{ __('general.footer_cv_url') }}" target="_blank" class="flex items-center gap-2 hover:text-sky-700 dark:hover:text-sky-400 transition-colors">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ __('general.footer_download_cv') }}
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Tech Stack --}}
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 text-sm mb-4 uppercase tracking-wide">{{ __('general.footer_tech_stack') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Laravel', 'PHP 8.5', 'Tailwind CSS', 'Alpine.js', 'Docker', 'Kubernetes', 'MySQL', 'RabbitMQ', 'Nginx'] as $tech)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-200/60 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                                {{ $tech }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-800">
                <p class="text-center text-sm text-gray-400 dark:text-gray-500">&copy; {{ date('Y') }} Szymon Borowski. {{ __('general.all_rights_reserved') }}</p>
            </div>
        </div>
    </footer>

    @livewireScripts
    <x-chat-widget />
</body>
</html>
