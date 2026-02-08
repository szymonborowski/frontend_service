@extends('layouts.app')

@section('title', $post['title'] ?? __('general.blog'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Left column - recent posts --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('general.recent_posts') }}</h2>
                    <ul class="space-y-3">
                        @forelse($recentPosts as $p)
                            <li>
                                <a href="{{ route('post.show', $p['slug']) }}" class="block group {{ ($p['id'] ?? $p['slug']) === ($post['id'] ?? $post['slug']) ? 'font-medium' : '' }}">
                                    <h3 class="text-sm font-medium text-gray-900 group-hover:text-sky-800 line-clamp-2">
                                        {{ $p['title'] }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($p['published_at'])->format('d.m.Y') }}
                                    </p>
                                </a>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">{{ __('general.no_posts') }}</li>
                        @endforelse
                    </ul>
                </div>
            </aside>

            {{-- Middle column - single post --}}
            <main class="lg:col-span-2">
                <article class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center space-x-2 mb-3">
                            @if($post['categories'] ?? [])
                                @foreach($post['categories'] as $category)
                                    <a href="{{ route('category.show', $category['slug']) }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800 hover:bg-sky-200">
                                        {{ $category['name'] }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $post['title'] }}</h1>
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <time datetime="{{ $post['published_at'] }}">
                                {{ \Carbon\Carbon::parse($post['published_at'])->format('d F Y') }}
                            </time>
                        </div>
                        @if($post['excerpt'] ?? null)
                            <p class="text-gray-600 mb-4">{{ $post['excerpt'] }}</p>
                        @endif
                        <div class="prose prose-gray max-w-none">
                            {!! \Illuminate\Support\Str::markdown($post['content'] ?? '') !!}
                        </div>
                        @if($post['tags'] ?? [])
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post['tags'] as $tag)
                                        <a href="{{ route('tag.show', $tag['slug']) }}" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600 hover:bg-sky-100 hover:text-sky-800">
                                            #{{ $tag['name'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </article>
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
