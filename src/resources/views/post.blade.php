@extends('layouts.app')

@section('title', $post['title'] ?? __('general.blog'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Left column - recent posts --}}
            <aside class="lg:col-span-1">
                <x-recent-posts-sidebar :recentPosts="$recentPosts" />
            </aside>

            {{-- Middle column - single post --}}
            <main class="lg:col-span-2">
                <article class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center space-x-2 mb-3">
                            @if($post['categories'] ?? [])
                                @foreach($post['categories'] as $category)
                                    <x-category-badge
                                        :name="$category['name']"
                                        :slug="$category['slug']"
                                        :color="$category['color'] ?? null"
                                        :href="route('category.show', $category['slug'])"
                                    />
                                @endforeach
                            @endif
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">{{ $post['title'] }}</h1>
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <time datetime="{{ $post['published_at'] }}">
                                {{ \Carbon\Carbon::parse($post['published_at'])->format('d F Y') }}
                            </time>
                        </div>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            {!! \Illuminate\Support\Str::markdown($post['content'] ?? '') !!}
                        </div>
                        @if($post['tags'] ?? [])
                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post['tags'] as $tag)
                                        <a href="{{ route('tag.show', $tag['slug']) }}" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30 hover:text-sky-700 dark:hover:text-sky-400">
                                            #{{ $tag['name'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Like button (bottom) --}}
                        @if($post['uuid'] ?? null)
                            @php
                                $postLike = $likesData['post:' . $post['uuid']] ?? ['count' => 0, 'liked' => false];
                            @endphp
                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center gap-3">
                                <x-like-button
                                    type="post"
                                    :id="$post['uuid']"
                                    :count="$postLike['count']"
                                    :liked="$postLike['liked']"
                                />
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.likes') }}</span>
                            </div>
                        @endif

                        {{-- Edit button for author --}}
                        @if(session('user_id') && session('user_id') == ($post['author']['user_id'] ?? null))
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('panel.posts.edit', $post['id']) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-sky-700 dark:text-sky-400 bg-sky-50 dark:bg-sky-900/20 rounded hover:bg-sky-100 dark:hover:bg-sky-900/40">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    {{ __('posts.edit_post') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </article>

                {{-- Comments section --}}
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6" id="comments">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('general.comments') }}
                        @if(($commentsMeta['total'] ?? 0) > 0)
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $commentsMeta['total'] }})</span>
                        @endif
                    </h2>

                    @forelse($comments as $comment)
                        <div class="py-4 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ __('general.user') }} #{{ $comment['author_id'] }}
                                </span>
                                <time class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ \Carbon\Carbon::parse($comment['created_at'])->format('d.m.Y H:i') }}
                                </time>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $comment['content'] }}</p>
                            @if(isset($comment['id']))
                                @php
                                    $commentLike = $likesData['comment:' . $comment['id']] ?? ['count' => 0, 'liked' => false];
                                @endphp
                                <div class="mt-2">
                                    <x-like-button
                                        type="comment"
                                        :id="(string) $comment['id']"
                                        :count="$commentLike['count']"
                                        :liked="$commentLike['liked']"
                                        size="sm"
                                    />
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.no_comments') }}</p>
                    @endforelse

                    @if(($commentsMeta['total'] ?? 0) > count($comments))
                        <div class="mt-4 text-center">
                            <a href="{{ request()->fullUrlWithQuery(['comments_page' => ($commentsMeta['current_page'] ?? 1) + 1]) }}#comments"
                               class="text-sm text-sky-700 dark:text-sky-400 hover:underline">
                                {{ __('general.load_more_comments') }}
                            </a>
                        </div>
                    @endif
                </div>
            </main>

            {{-- Right column - categories and tags --}}
            <aside class="lg:col-span-1 space-y-6">
                <x-category-grid :categories="$categories" />
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('general.tags') }}</h2>
                    <div class="flex flex-wrap gap-2">
                        @forelse($tags as $tag)
                            <a href="{{ route('tag.show', $tag['slug']) }}" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30 hover:text-sky-700 dark:hover:text-sky-400">
                                #{{ $tag['name'] }}
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
