@extends('layouts.app')

@section('title', __('general.home'))

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

            {{-- Middle column - featured post --}}
            <main class="lg:col-span-2">
                @if($featuredPost)
                    <article class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center space-x-2 mb-3">
                                @if($featuredPost['categories'] ?? [])
                                    @foreach($featuredPost['categories'] as $category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                            {{ $category['name'] }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-3">
                                <a href="{{ route('post.show', $featuredPost['slug']) }}" class="hover:text-sky-800">
                                    {{ $featuredPost['title'] }}
                                </a>
                            </h1>
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <time datetime="{{ $featuredPost['published_at'] }}">
                                    {{ \Carbon\Carbon::parse($featuredPost['published_at'])->format('d F Y') }}
                                </time>
                            </div>
                            @if($featuredPost['excerpt'])
                                <p class="text-gray-600 mb-4">{{ $featuredPost['excerpt'] }}</p>
                            @endif
                            <div class="prose prose-gray max-w-none">
                                {!! \Illuminate\Support\Str::markdown($featuredPost['content']) !!}
                            </div>
                            @if($featuredPost['tags'] ?? [])
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($featuredPost['tags'] as $tag)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                                #{{ $tag['name'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </article>
                @else
                    <div class="bg-white rounded-lg shadow p-6">
                        <p class="text-gray-500 text-center">{{ __('general.no_posts_to_display') }}</p>
                    </div>
                @endif
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
