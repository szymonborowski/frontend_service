@extends('layouts.app')

@section('title', $post['title'] ?? __('general.blog'))

@section('og_type', 'article')
@section('og_title', $post['title'] ?? __('general.blog'))
@section('og_description', Str::limit(strip_tags(Str::markdown($post['content'] ?? '')), 160))
@if(!empty($post['cover_image']))
    @section('og_image', $post['cover_image'])
@endif

@push('head')
    {{-- article: meta tags --}}
    @isset($post['published_at'])
        <meta property="article:published_time" content="{{ \Carbon\Carbon::parse($post['published_at'])->toIso8601String() }}">
    @endisset
    @isset($post['updated_at'])
        <meta property="article:modified_time" content="{{ \Carbon\Carbon::parse($post['updated_at'])->toIso8601String() }}">
    @endisset
    @if(!empty($post['author']['name']))
        <meta property="article:author" content="{{ $post['author']['name'] }}">
    @endif
    @if(!empty($post['tags']))
        @foreach($post['tags'] as $tag)
            <meta property="article:tag" content="{{ $tag['name'] ?? '' }}">
        @endforeach
    @endif

    {{-- BlogPosting JSON-LD --}}
    @php
        $blogPostingLd = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post['title'] ?? '',
            'image' => $post['cover_image'] ?? url('/images/og-cover.png'),
            'datePublished' => isset($post['published_at']) ? \Carbon\Carbon::parse($post['published_at'])->toIso8601String() : null,
            'dateModified' => isset($post['updated_at']) ? \Carbon\Carbon::parse($post['updated_at'])->toIso8601String() : null,
            'author' => [
                '@type' => 'Person',
                'name' => $post['author']['name'] ?? 'Szymon Borowski',
                'url' => url('/'),
            ],
            'publisher' => [
                '@type' => 'Person',
                'name' => 'Szymon Borowski',
                'url' => url('/'),
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => url()->current(),
            ],
            'description' => Str::limit(strip_tags(Str::markdown($post['content'] ?? '')), 160),
            'inLanguage' => $post['locale'] ?? app()->getLocale(),
        ];
        $blogPostingLd = array_filter($blogPostingLd, fn($v) => $v !== null);
    @endphp
    <script type="application/ld+json">{!! json_encode($blogPostingLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Left column - recent posts --}}
            <aside class="lg:col-span-1">
                <x-recent-posts-sidebar :recentPosts="$recentPosts" />
            </aside>

            {{-- Middle column - single post --}}
            <main class="lg:col-span-2">
                <article class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
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
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3">{{ $post['title'] }}</h1>
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                            @if($post['author']['name'] ?? null)
                                <span>{{ $post['author']['name'] }}</span>
                                <span class="mx-2">&middot;</span>
                            @endif
                            <time datetime="{{ $post['published_at'] }}">
                                {{ \Carbon\Carbon::parse($post['published_at'])->format('d F Y') }}
                            </time>
                        </div>
                        <div class="prose prose-gray dark:prose-invert max-w-none"
                             x-data
                             @click="if ($event.target.tagName === 'IMG') $dispatch('open-lightbox', { src: $event.target.src, alt: $event.target.alt })"
                        >
                            {!! \Illuminate\Support\Str::markdown($post['content'] ?? '') !!}
                        </div>
                        @if($post['tags'] ?? [])
                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post['tags'] as $tag)
                                        <a href="{{ route('tag.show', $tag['slug']) }}" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-200/60 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30 hover:text-sky-700 dark:hover:text-sky-400">
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
                        @if(session('user.id') && session('user.id') == ($post['author']['user_id'] ?? null))
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

                {{-- Comment form for eligible users / login prompt for guests --}}
                @if(session('access_token') && $canComment)
                    <div class="mt-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('comments.add_comment') }}</h3>

                        @if(session('comment_success'))
                            <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded text-sm text-green-800 dark:text-green-300">
                                {{ __('comments.store_success') }}
                            </div>
                        @endif

                        @if($errors->has('comment_content'))
                            <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded text-sm text-red-800 dark:text-red-300">
                                {{ $errors->first('comment_content') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('post.comments.store', $post['id']) }}">
                            @csrf
                            <div x-data="{ chars: {{ strlen(old('content', '')) }} }">
                                <textarea
                                    name="content"
                                    rows="4"
                                    class="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-sky-500"
                                    placeholder="{{ __('comments.content_placeholder') }}"
                                    required
                                    minlength="3"
                                    maxlength="5000"
                                    @input="chars = $el.value.length"
                                >{{ old('content') }}</textarea>
                                <div class="mt-1 flex justify-end text-xs"
                                     :class="chars >= 5000 ? 'text-red-500' : (chars >= 4500 ? 'text-amber-500' : 'text-gray-400 dark:text-gray-500')">
                                    <span x-text="chars"></span>&nbsp;/ 5000
                                </div>
                            </div>
                            <div class="mt-3 flex justify-end">
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-sky-600 hover:bg-sky-700 rounded">
                                    {{ __('comments.add_comment') }}
                                </button>
                            </div>
                        </form>
                    </div>
                @elseif(!session('access_token'))
                    <div class="mt-6 bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-800 rounded-lg p-4 text-sm text-sky-800 dark:text-sky-300">
                        <a href="{{ route('login') }}" class="font-medium underline hover:text-sky-600 dark:hover:text-sky-400">{{ __('general.login') }}</a>
                        — {{ __('general.login_to_comment') }}
                    </div>
                @endif

                {{-- Comments section --}}
                <div class="mt-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6" id="comments">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                        {{ __('general.comments') }}
                        @if(($commentsMeta['total'] ?? 0) > 0)
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $commentsMeta['total'] }})</span>
                        @endif
                    </h2>

                    @forelse($comments as $comment)
                        <div class="py-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $comment['author']['name'] ?? __('general.user') . ' #' . $comment['author_id'] }}
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
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('general.tags') }}</h2>
                    <div class="flex flex-wrap gap-2">
                        @forelse($tags as $tag)
                            <a href="{{ route('tag.show', $tag['slug']) }}" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-200/60 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30 hover:text-sky-700 dark:hover:text-sky-400">
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

    <x-image-lightbox />
@endsection
