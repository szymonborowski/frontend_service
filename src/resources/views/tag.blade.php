@extends('layouts.app')

@section('title', '#' . ($tag['name'] ?? '') . ' - ' . __('general.tags'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

            {{-- Middle column - list of posts with this tag --}}
            <main class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">#{{ $tag['name'] }}</h1>
                    @if(($tag['posts_count'] ?? 0) > 0)
                        <p class="text-sm text-gray-500 mb-6">{{ __('general.posts_count', ['count' => $tag['posts_count']]) }}</p>
                    @endif
                    <ul class="space-y-6">
                        @forelse($posts as $post)
                            <li class="border-b border-gray-100 last:border-0 pb-6 last:pb-0">
                                <article>
                                    <div class="flex items-center space-x-2 mb-2">
                                        @if($post['categories'] ?? [])
                                            @foreach($post['categories'] as $category)
                                                <a href="{{ route('category.show', $category['slug']) }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800 hover:bg-sky-200">
                                                    {{ $category['name'] }}
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                    <h2 class="text-lg font-semibold text-gray-900 mb-1">
                                        <a href="{{ route('post.show', $post['slug']) }}" class="hover:text-sky-800">
                                            {{ $post['title'] }}
                                        </a>
                                    </h2>
                                    <time datetime="{{ $post['published_at'] }}" class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($post['published_at'])->format('d F Y') }}
                                    </time>
                                    @if($post['excerpt'] ?? null)
                                        <p class="text-gray-600 mt-2 text-sm">{{ Str::limit($post['excerpt'], 160) }}</p>
                                    @endif
                                    @if($post['tags'] ?? [])
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            @foreach($post['tags'] as $t)
                                                <a href="{{ route('tag.show', $t['slug']) }}" class="text-xs text-gray-500 hover:text-sky-800 {{ ($t['slug'] ?? '') === ($tag['slug'] ?? '') ? 'font-medium text-sky-800' : '' }}">#{{ $t['name'] }}</a>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>
                            </li>
                        @empty
                            <li class="text-gray-500">{{ __('general.no_posts') }}</li>
                        @endforelse
                    </ul>
                    <x-pagination
                        :paginationRoute="$paginationRoute"
                        :paginationRouteParams="$paginationRouteParams"
                        :meta="$meta"
                        :currentPerPage="$currentPerPage"
                        :allowedPerPage="$allowedPerPage"
                    />
                </div>
            </main>

            {{-- Right column - categories and tags --}}
            <aside class="lg:col-span-1 space-y-6">
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
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('general.tags') }}</h2>
                    <div class="flex flex-wrap gap-2">
                        @forelse($tags as $t)
                            <a href="{{ route('tag.show', $t['slug']) }}" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600 hover:bg-sky-100 hover:text-sky-800 {{ ($t['slug'] ?? '') === ($tag['slug'] ?? '') ? 'bg-sky-100 text-sky-800' : '' }}">
                                #{{ $t['name'] }}
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
