<div
    x-data="{ focused: false }"
    @keydown.escape.window="$wire.close()"
    class="relative"
>
    {{-- Lupa --}}
    <button
        @click="$wire.open()"
        type="button"
        class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-sky-700 dark:hover:text-sky-400 hover:bg-gray-200/60 dark:hover:bg-gray-700/60 transition-colors"
        aria-label="{{ __('general.search') }}"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/>
        </svg>
    </button>

    {{-- Overlay + panel --}}
    @if($isOpen)
    <div
        class="fixed inset-0 z-50"
        @click.self="$wire.close()"
        style="background: rgba(0,0,0,0.4); backdrop-filter: blur(2px);"
    >
        <div class="flex items-start justify-center pt-24 px-4">
            <div class="w-full max-w-2xl relative">

                {{-- Przycisk zamknij — prawy górny róg panelu --}}
                <button
                    @click="$wire.close()"
                    type="button"
                    class="absolute -top-10 right-0 flex items-center gap-1.5 text-gray-300 hover:text-white transition-colors text-sm"
                    aria-label="Zamknij"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <kbd class="font-mono text-xs bg-white/10 px-1.5 py-0.5 rounded">ESC</kbd>
                </button>

                {{-- Input --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        @if($loading)
                        <svg class="w-5 h-5 text-sky-600 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        @else
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/>
                        </svg>
                        @endif
                    </div>
                    <input
                        wire:model.live.debounce.300ms="query"
                        x-ref="searchInput"
                        x-init="$nextTick(() => $el.focus())"
                        @keydown.enter.prevent="if ($wire.query.trim().length >= 2) { window.location = '{{ route('posts.index') }}?q=' + encodeURIComponent($wire.query.trim()); }"
                        type="text"
                        placeholder="{{ __('general.search_placeholder') }}"
                        class="w-full pl-12 pr-4 py-4 text-base bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400"
                        autocomplete="off"
                        spellcheck="false"
                    >
                </div>

                {{-- Wyniki --}}
                @if(mb_strlen($query) >= 2)
                <div class="mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl overflow-hidden divide-y divide-gray-100 dark:divide-gray-700">

                    {{-- Posty --}}
                    @if(!empty($results['posts']))
                    <div class="px-4 py-3">
                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">{{ __('general.search_posts') }}</p>
                        <ul class="space-y-1">
                            @foreach($results['posts'] as $post)
                            <li>
                                <a
                                    href="{{ url('/post/' . ($post['slug'] ?? '')) }}"
                                    @click="$wire.close()"
                                    class="flex items-start gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/60 transition-colors group"
                                >
                                    @if(!empty($post['cover_image']))
                                    <img src="{{ $post['cover_image'] }}" alt="" class="w-10 h-10 rounded-md object-cover shrink-0 mt-0.5">
                                    @else
                                    <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-100 group-hover:text-sky-700 dark:group-hover:text-sky-400 truncate [&_mark]:bg-sky-100 [&_mark]:dark:bg-sky-900/60 [&_mark]:text-sky-800 [&_mark]:dark:text-sky-300 [&_mark]:rounded [&_mark]:px-0.5">
                                            {!! $post['_formatted']['title'] ?? e($post['title'] ?? '') !!}
                                        </p>
                                        @if(!empty($post['_formatted']['excerpt']) || !empty($post['excerpt']))
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1 [&_mark]:bg-sky-100 [&_mark]:dark:bg-sky-900/60 [&_mark]:text-sky-800 [&_mark]:dark:text-sky-300 [&_mark]:rounded [&_mark]:px-0.5">
                                            {!! $post['_formatted']['excerpt'] ?? e($post['excerpt'] ?? '') !!}
                                        </p>
                                        @endif
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Kategorie --}}
                    @if(!empty($results['categories']))
                    <div class="px-4 py-3">
                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">{{ __('general.search_categories') }}</p>
                        <ul class="flex flex-wrap gap-2">
                            @foreach($results['categories'] as $cat)
                            <li>
                                <a
                                    href="{{ url('/kategoria/' . ($cat['slug'] ?? '')) }}"
                                    @click="$wire.close()"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/40 hover:text-sky-700 dark:hover:text-sky-400 transition-colors"
                                >
                                    @if(!empty($cat['icon']))
                                    <span class="text-base leading-none">{{ $cat['icon'] }}</span>
                                    @endif
                                    {{ $cat['name'] ?? '' }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Tagi --}}
                    @if(!empty($results['tags']))
                    <div class="px-4 py-3">
                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">{{ __('general.search_tags') }}</p>
                        <ul class="flex flex-wrap gap-2">
                            @foreach($results['tags'] as $tag)
                            <li>
                                <a
                                    href="{{ url('/tag/' . ($tag['slug'] ?? '')) }}"
                                    @click="$wire.close()"
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/40 hover:text-sky-700 dark:hover:text-sky-400 transition-colors"
                                >
                                    #{{ $tag['name'] ?? '' }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Szymon Borowski --}}
                    @if($this->showAboutResult)
                    <div class="px-4 py-3">
                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">{{ __('general.search_about') }}</p>
                        <a
                            href="{{ url('/about') }}"
                            @click="$wire.close()"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/60 transition-colors group"
                        >
                            <img src="/images/me200x200.png" alt="Szymon Borowski" class="w-10 h-10 rounded-full object-cover shrink-0 ring-2 ring-gray-200 dark:ring-gray-600">
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-100 group-hover:text-sky-700 dark:group-hover:text-sky-400">Szymon Borowski</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PHP Developer · Laravel · DevOps · Koszalin</p>
                            </div>
                        </a>
                    </div>
                    @endif

                    {{-- Brak wynikow --}}
                    @if($this->isEmpty)
                    <div class="px-4 py-8 text-center">
                        <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/>
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.search_no_results', ['query' => $query]) }}</p>
                    </div>
                    @endif

                </div>
                @endif


            </div>
        </div>
    </div>
    @endif
</div>
