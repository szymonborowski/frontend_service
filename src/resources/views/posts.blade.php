@extends('layouts.app')

@section('title', $search
    ? __('general.posts_search_title', ['query' => $search])
    : __('general.posts_all_title'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- Left: Recent posts --}}
        <aside class="lg:col-span-1">
            <x-recent-posts-sidebar :recentPosts="$recentPosts" />
        </aside>

        {{-- Center: Post listing --}}
        <main class="lg:col-span-2">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">

                {{-- Header --}}
                <div class="mb-5">
                    @if($search)
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                            {{ __('general.posts_search_results') }}
                            <span class="text-sky-700 dark:text-sky-400">&ldquo;{{ $search }}&rdquo;</span>
                        </h1>
                    @else
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('general.posts_all_title') }}</h1>
                    @endif
                    @if(($meta['total'] ?? 0) > 0)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ __('general.posts_count', ['count' => $meta['total']]) }}
                        </p>
                    @endif
                </div>

                {{-- Filter bar --}}
                <div
                    x-data="{
                        categoryId: '{{ $categoryId ?? '' }}',
                        tagId: '{{ $tagId ?? '' }}',
                        sortBy: '{{ $sortBy }}',
                        sortOrder: '{{ $sortOrder }}',
                        buildUrl() {
                            const base = '{{ route('posts.index') }}';
                            const params = new URLSearchParams();
                            @if($search) params.set('q', @js($search)); @endif
                            if (this.categoryId) params.set('category_id', this.categoryId);
                            if (this.tagId) params.set('tag_id', this.tagId);
                            if (this.sortBy !== 'published_at') params.set('sort_by', this.sortBy);
                            if (this.sortOrder !== 'desc') params.set('sort_order', this.sortOrder);
                            const qs = params.toString();
                            return qs ? base + '?' + qs : base;
                        },
                        apply() { window.location = this.buildUrl(); }
                    }"
                    class="mb-5 flex flex-wrap items-center gap-2"
                >
                    {{-- Category filter --}}
                    <select
                        x-model="categoryId"
                        @change="apply()"
                        class="text-sm rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400"
                    >
                        <option value="">{{ __('general.posts_filter_all_categories') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                        @endforeach
                    </select>

                    {{-- Tag filter --}}
                    <select
                        x-model="tagId"
                        @change="apply()"
                        class="text-sm rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400"
                    >
                        <option value="">{{ __('general.posts_filter_all_tags') }}</option>
                        @foreach($tags as $t)
                            <option value="{{ $t['id'] }}">{{ $t['name'] }}</option>
                        @endforeach
                    </select>

                    {{-- Sort --}}
                    <select
                        x-model="sortOrder"
                        @change="apply()"
                        class="text-sm rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400"
                    >
                        <option value="desc">{{ __('general.posts_sort_newest') }}</option>
                        <option value="asc">{{ __('general.posts_sort_oldest') }}</option>
                    </select>

                    {{-- Active filter badges --}}
                    @if($search || $activeCategory || $activeTag)
                        <div class="flex flex-wrap items-center gap-1.5 ml-1">
                            @if($search)
                                <a
                                    href="{{ route('posts.index', array_filter(['category_id' => $categoryId, 'tag_id' => $tagId, 'sort_order' => $sortOrder !== 'desc' ? $sortOrder : null])) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-sky-100 dark:bg-sky-900/40 text-sky-700 dark:text-sky-300 hover:bg-sky-200 dark:hover:bg-sky-900/60 transition-colors"
                                >
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/></svg>
                                    {{ Str::limit($search, 20) }}
                                    <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                            @if($activeCategory)
                                <a
                                    href="{{ route('posts.index', array_filter(['q' => $search, 'tag_id' => $tagId, 'sort_order' => $sortOrder !== 'desc' ? $sortOrder : null])) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-200 dark:hover:bg-emerald-900/60 transition-colors"
                                >
                                    {{ $activeCategory['name'] }}
                                    <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                            @if($activeTag)
                                <a
                                    href="{{ route('posts.index', array_filter(['q' => $search, 'category_id' => $categoryId, 'sort_order' => $sortOrder !== 'desc' ? $sortOrder : null])) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-300 hover:bg-violet-200 dark:hover:bg-violet-900/60 transition-colors"
                                >
                                    #{{ $activeTag['name'] }}
                                    <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Post list --}}
                <ul class="space-y-6">
                    @forelse($posts as $post)
                        <li class="border-b border-gray-200 dark:border-gray-700 last:border-0 pb-6 last:pb-0">
                            <article>
                                <div class="flex items-center flex-wrap gap-1.5 mb-2">
                                    @foreach($post['categories'] ?? [] as $cat)
                                        <x-category-badge
                                            :name="$cat['name']"
                                            :slug="$cat['slug']"
                                            :color="$cat['color'] ?? null"
                                            :href="route('category.show', $cat['slug'])"
                                        />
                                    @endforeach
                                </div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-1">
                                    <a href="{{ route('post.show', $post['slug']) }}" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors">
                                        {{ $post['title'] }}
                                    </a>
                                </h2>
                                <time datetime="{{ $post['published_at'] }}" class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($post['published_at'])->translatedFormat('d F Y') }}
                                </time>
                                @if($post['excerpt'] ?? null)
                                    <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">{{ Str::limit($post['excerpt'], 160) }}</p>
                                @endif
                                @if($post['tags'] ?? [])
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach($post['tags'] as $tag)
                                            <a href="{{ route('tag.show', $tag['slug']) }}" class="text-xs text-gray-500 dark:text-gray-400 hover:text-sky-700 dark:hover:text-sky-400 transition-colors">#{{ $tag['name'] }}</a>
                                        @endforeach
                                    </div>
                                @endif
                            </article>
                        </li>
                    @empty
                        <li class="py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('general.no_posts') }}</p>
                            @if($search || $activeCategory || $activeTag)
                                <a href="{{ route('posts.index') }}" class="mt-3 inline-block text-sm text-sky-700 dark:text-sky-400 hover:underline">
                                    {{ __('general.posts_clear_filters') }}
                                </a>
                            @endif
                        </li>
                    @endforelse
                </ul>

                <x-pagination
                    :paginationRoute="$paginationRoute"
                    :extraParams="$extraParams"
                    :meta="$meta"
                    :currentPerPage="$currentPerPage"
                    :allowedPerPage="$allowedPerPage"
                />
            </div>
        </main>

        {{-- Right: Categories + Tags --}}
        <aside class="lg:col-span-1 space-y-6">
            <x-category-grid :categories="$categories" />
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('general.tags') }}</h2>
                <div class="flex flex-wrap gap-2">
                    @forelse($tags as $t)
                        <a
                            href="{{ route('posts.index', array_filter(['q' => $search, 'tag_id' => $t['id'], 'sort_order' => $sortOrder !== 'desc' ? $sortOrder : null])) }}"
                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium transition-colors
                                {{ (int)($tagId ?? 0) === (int)$t['id']
                                    ? 'bg-sky-700 text-white'
                                    : 'bg-gray-200/60 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30 hover:text-sky-700 dark:hover:text-sky-400' }}"
                        >
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
