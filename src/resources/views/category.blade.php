@extends('layouts.app')

@section('title', $category['name'] ?? __('general.categories'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Left column - recent posts --}}
            <aside class="lg:col-span-1">
                <x-recent-posts-sidebar :recentPosts="$recentPosts" />
            </aside>

            {{-- Middle column - list of posts in this category --}}
            <main class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $category['name'] }}</h1>
                    @if(($category['posts_count'] ?? 0) > 0)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ __('general.posts_count', ['count' => $category['posts_count']]) }}</p>
                    @endif
                    <ul class="space-y-6">
                        @forelse($posts as $post)
                            <li class="border-b border-gray-100 dark:border-gray-700 last:border-0 pb-6 last:pb-0">
                                <article>
                                    <div class="flex items-center space-x-2 mb-2">
                                        @if($post['categories'] ?? [])
                                            @foreach($post['categories'] as $cat)
                                                <x-category-badge
                                                    :name="$cat['name']"
                                                    :slug="$cat['slug']"
                                                    :color="$cat['color'] ?? null"
                                                    :href="route('category.show', $cat['slug'])"
                                                />
                                            @endforeach
                                        @endif
                                    </div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                        <a href="{{ route('post.show', $post['slug']) }}" class="hover:text-sky-700 dark:hover:text-sky-400">
                                            {{ $post['title'] }}
                                        </a>
                                    </h2>
                                    <time datetime="{{ $post['published_at'] }}" class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($post['published_at'])->format('d F Y') }}
                                    </time>
                                    @if($post['excerpt'] ?? null)
                                        <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">{{ Str::limit($post['excerpt'], 160) }}</p>
                                    @endif
                                    @if($post['tags'] ?? [])
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            @foreach($post['tags'] as $tag)
                                                <a href="{{ route('tag.show', $tag['slug']) }}" class="text-xs text-gray-500 dark:text-gray-400 hover:text-sky-700 dark:hover:text-sky-400">#{{ $tag['name'] }}</a>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>
                            </li>
                        @empty
                            <li class="text-gray-500 dark:text-gray-400">{{ __('general.no_posts') }}</li>
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
                <x-category-grid :categories="$categories" />
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('general.tags') }}</h2>
                    <div class="flex flex-wrap gap-2">
                        @forelse($tags as $t)
                            <a href="{{ route('tag.show', $t['slug']) }}" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30 hover:text-sky-700 dark:hover:text-sky-400">
                                #{{ $t['name'] }}
                            </a>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.no_tags') }}</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
