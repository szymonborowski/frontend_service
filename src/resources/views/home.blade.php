@extends('layouts.app')

@section('title', __('general.home'))

@section('content')
    {{-- Hero Section --}}
    <x-hero-section />

    {{-- Bento Grid Content --}}
    <div id="posts" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Bento Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Featured Post (spans 2 cols on lg) --}}
            @if(!empty($mostImportantPosts) && count($mostImportantPosts) > 0)
                <div class="md:col-span-2 opacity-0" x-data x-init="fadeInOnScroll($el)">
                    <div class="relative">
                        <span class="absolute -top-3 left-4 z-10 inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-sky-600 text-white shadow-sm">
                            {{ __('general.featured_post') }}
                        </span>
                        <x-post-card :post="$mostImportantPosts[0]" :featured="true" />
                    </div>
                </div>
            @endif

            {{-- About Me mini card --}}
            <div class="opacity-0 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col justify-between" x-data x-init="fadeInOnScroll($el)">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <img src="/images/me200x200.png" alt="Szymon Borowski" class="w-16 h-16 rounded-full ring-2 ring-gray-200 dark:ring-gray-700">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Szymon Borowski</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Software Developer</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ __('general.hero_subtitle') }}
                    </p>
                </div>
                <a href="{{ url('/about') }}" class="mt-4 inline-flex items-center text-sm font-medium text-sky-700 dark:text-sky-400 hover:text-sky-600 dark:hover:text-sky-300 transition-colors">
                    {{ __('general.hero_about_me') }}
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Post cards (row 2) --}}
            @foreach(array_slice($mostImportantPosts, 1, 2) as $index => $post)
                <div class="opacity-0 stagger-{{ $index + 1 }}" x-data x-init="fadeInOnScroll($el)">
                    <x-post-card :post="$post" />
                </div>
            @endforeach

            {{-- Categories grid --}}
            <div class="opacity-0" x-data x-init="fadeInOnScroll($el)">
                <x-category-grid :categories="$categories" />
            </div>

            {{-- More post cards (row 3) --}}
            @foreach(array_slice($mostImportantPosts, 3, 3) as $index => $post)
                <div class="opacity-0 stagger-{{ $index + 1 }}" x-data x-init="fadeInOnScroll($el)">
                    <x-post-card :post="$post" />
                </div>
            @endforeach
        </div>

        {{-- Tags bar --}}
        <div class="mt-8 opacity-0" x-data x-init="fadeInOnScroll($el)">
            <x-tags-bar :tags="$tags" />
        </div>

        {{-- Recent Posts Section --}}
        @if(!empty($recentPosts))
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 opacity-0" x-data x-init="fadeInOnScroll($el)">
                    {{ __('general.recent_posts') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recentPosts as $index => $post)
                        <div class="opacity-0 stagger-{{ ($index % 6) + 1 }}" x-data x-init="fadeInOnScroll($el)">
                            <x-post-card :post="$post" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@section('pre-footer')
    <x-newsletter-cta />
@endsection
