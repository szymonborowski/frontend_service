@extends('layouts.app')

@section('title', __('general.home'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(!empty($slides))
            <x-hero-slider :slides="$slides" />
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Left column - recent posts --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('general.recent_posts') }}</h2>
                    <ul class="space-y-3">
                        @forelse($recentPosts as $post)
                            <li>
                                <a href="{{ route('post.show', $post['slug']) }}" class="block group">
                                    <h3 class="text-sm font-medium text-gray-900 group-hover:text-sky-800 line-clamp-2">
                                        {{ $post['title'] }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($post['published_at'])->format('d.m.Y') }}
                                    </p>
                                </a>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">{{ __('general.no_posts') }}</li>
                        @endforelse
                    </ul>
                </div>
            </aside>

            {{-- Middle column - most important posts --}}
            <main class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 pt-6 pb-3 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">{{ __('general.most_important_posts') }}</h2>
                        <p class="text-sm text-gray-500 mt-0.5">{{ __('general.most_important_posts_subtitle') }}</p>
                    </div>

                    @forelse($mostImportantPosts as $post)
                        <article class="px-6 py-5 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">
                            {{-- Categories --}}
                            @if(!empty($post['categories']))
                                <div class="flex flex-wrap gap-1.5 mb-2">
                                    @foreach($post['categories'] as $category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                            {{ $category['name'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Title --}}
                            <h3 class="text-base font-semibold text-gray-900 mb-1">
                                <a href="{{ route('post.show', $post['slug']) }}" class="hover:text-sky-800 transition-colors">
                                    {{ $post['title'] }}
                                </a>
                            </h3>

                            {{-- Excerpt --}}
                            @if(!empty($post['excerpt']))
                                <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ $post['excerpt'] }}</p>
                            @endif

                            {{-- Meta --}}
                            <div class="flex items-center justify-between">
                                <time class="text-xs text-gray-400" datetime="{{ $post['published_at'] }}">
                                    {{ \Carbon\Carbon::parse($post['published_at'])->format('d.m.Y') }}
                                </time>
                                <a href="{{ route('post.show', $post['slug']) }}" class="text-xs font-medium text-sky-700 hover:text-sky-900 transition-colors">
                                    {{ __('general.read_more') }} →
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="px-6 py-10 text-center text-gray-400 text-sm">
                            {{ __('general.no_posts_to_display') }}
                        </div>
                    @endforelse
                </div>
            </main>

            {{-- Right column - categories and tags --}}
            <aside class="lg:col-span-1 space-y-6">
                {{-- Categories --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('general.categories') }}</h2>
                    <ul class="space-y-2">
                        @forelse($categories as $category)
                            <li>
                                <a href="{{ route('category.show', $category['slug']) }}" class="flex items-center justify-between text-sm text-gray-600 hover:text-sky-800">
                                    <span>{{ $category['name'] }}</span>
                                    <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">
                                        {{ $category['posts_count'] ?? 0 }}
                                    </span>
                                </a>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">{{ __('general.no_categories') }}</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Tags --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('general.tags') }}</h2>
                    <div class="flex flex-wrap gap-2">
                        @forelse($tags as $tag)
                            <a href="{{ route('tag.show', $tag['slug']) }}" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600 hover:bg-sky-100 hover:text-sky-800">
                                #{{ $tag['name'] }}
                            </a>
                        @empty
                            <p class="text-sm text-gray-500">{{ __('general.no_tags') }}</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
