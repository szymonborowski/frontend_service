@props([
    'greeting' => null,
    'tagline'  => null,
    'subtitle' => null,
    'skills'   => null,
])

@php
    $greeting ??= __('general.hero_greeting');
    $tagline  ??= __('general.hero_tagline');
    $subtitle ??= __('general.hero_subtitle');
@endphp

{{-- Hero Section — bold typography + journey artwork --}}
<section class="relative overflow-hidden flex items-start bg-gradient-to-b from-slate-900 to-slate-950">
    {{-- Journey artwork (fit to height, centered, edge color fills sides) --}}
    <div class="absolute inset-0 dark:hidden" style="background-color: #f3f4f6;">
        <img src="/images/journey-light.webp"
             alt=""
             class="w-full h-full object-contain object-top opacity-25">
    </div>
    <div class="absolute inset-0 hidden dark:block" style="background-color: #0a0911;">
        <img src="/images/journey-dark.webp"
             alt=""
             class="w-full h-full object-contain object-top opacity-25">
    </div>

    {{-- Subtle grid pattern --}}
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23000000&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    {{-- Dark mode grid (white crosses) --}}
    <div class="absolute inset-0 opacity-0 dark:opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-7">
                {{-- Greeting --}}
                <p class="text-sky-600 dark:text-sky-400 font-mono text-sm sm:text-base mb-4 tracking-wide">
                    {{ $greeting }}
                </p>

                {{-- Title --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-4 leading-tight">
                    @if (!isset($title) || $title->isEmpty())
                        <span class="text-gray-800 dark:text-white">Extended</span><span class="text-gray-400 dark:text-gray-500">\</span><br class="sm:hidden">
                        <span class="text-gradient bg-gradient-to-r from-sky-500 via-indigo-500 to-violet-500 dark:from-sky-400 dark:via-indigo-400 dark:to-violet-400">Mind<span class="text-sky-600 dark:text-sky-400">::</span>Thesis</span><span class="text-gray-400 dark:text-gray-500">()</span>
                    @else
                        {{ $title }}
                    @endif
                </h1>

                {{-- Tagline --}}
                <h2 class="text-xl sm:text-2xl font-mono text-gray-500 dark:text-gray-400 mb-6 tracking-wide">
                    {{ $tagline }}
                </h2>

                {{-- Subtitle --}}
                <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-2xl leading-relaxed">
                    {{ $subtitle }}
                </p>

                {{-- CTA buttons --}}
                <div class="flex flex-wrap gap-4">
                    @if (!isset($buttons) || $buttons->isEmpty())
                        <button type="button"
                                onclick="window.dispatchEvent(new CustomEvent('open-chat'))"
                                class="inline-flex items-center px-6 py-3 rounded-lg bg-rose-700 hover:bg-rose-600 text-white font-medium transition-colors shadow-lg shadow-rose-700/30">
                            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            {{ __('general.hero_chat_cta') }}
                        </button>
                        <a href="#posts" class="inline-flex items-center px-6 py-3 rounded-lg bg-sky-600 hover:bg-sky-500 text-white font-medium transition-colors shadow-lg shadow-sky-600/25">
                            {{ __('general.hero_read_blog') }}
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>
                        <a href="{{ url('/collaboration') }}" class="inline-flex items-center px-6 py-3 rounded-lg bg-gray-800/10 hover:bg-gray-800/20 text-gray-700 dark:bg-white/10 dark:hover:bg-white/20 dark:text-white font-medium transition-colors border border-gray-800/20 dark:border-white/20 backdrop-blur-sm">
                            {{ __('general.hero_collaboration') }}
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        {{ $buttons }}
                    @endif
                </div>
            </div>

            {{-- Tech stack constellation (scattered cloud on desktop, wrap on mobile) --}}
            <div class="lg:col-span-5">
                <x-skill-cloud :skills="$skills" />
            </div>
        </div>
    </div>
</section>
