@extends('layouts.app')

@section('title', __('general.home'))

@section('content')
    {{-- Hero Section --}}
    <x-hero-section />

    <div id="posts" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Top row: About Me | GitHub Commits | Categories --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
            <div class="opacity-0" x-data x-init="fadeInOnScroll($el)">
                <x-about-mini-card class="h-full" />
            </div>
            <div class="opacity-0 stagger-1" x-data x-init="fadeInOnScroll($el)">
                <x-github-commits :commits="$githubCommits" :profile-url="$githubProfileUrl" class="h-full" />
            </div>
            <div class="opacity-0 stagger-2" x-data x-init="fadeInOnScroll($el)">
                <x-category-grid :categories="$categories" class="h-full" />
            </div>
        </div>

        {{-- Featured Posts --}}
        @if(!empty($mostImportantPosts))
            <div class="mt-8 space-y-4">
                @foreach($mostImportantPosts as $index => $post)
                    <div class="opacity-0 stagger-{{ ($index % 3) + 1 }}" x-data x-init="fadeInOnScroll($el)">
                        <div class="relative">
                            <span class="absolute -top-3 left-4 z-10 inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-sky-600 text-white shadow-sm">
                                {{ __('general.featured_post') }}
                            </span>
                            <x-post-card :post="$post" :featured="true" />
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Tags bar --}}
        <div class="mt-8 opacity-0" x-data x-init="fadeInOnScroll($el)">
            <x-tags-bar :tags="$tags" />
        </div>

        {{-- Recent Articles (non-feature) --}}
        @if(!empty($recentArticles))
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 opacity-0" x-data x-init="fadeInOnScroll($el)">
                    {{ __('general.recent_posts') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recentArticles as $index => $post)
                        <div class="opacity-0 stagger-{{ $index % 6 + 1 }}" x-data x-init="fadeInOnScroll($el)">
                            <x-post-card :post="$post" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Recent Features (dev-log) --}}
        @if(!empty($recentFeatures))
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 opacity-0" x-data x-init="fadeInOnScroll($el)">
                    {{ __('general.recent_features') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($recentFeatures as $index => $post)
                        <div class="opacity-0 stagger-{{ $index % 3 + 1 }}" x-data x-init="fadeInOnScroll($el)">
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
