@props(['post', 'featured' => false])

@php
    $primaryCategory = $post['categories'][0] ?? null;
    $categorySlug = $primaryCategory['slug'] ?? 'default';
    $categoryColor = $primaryCategory['color'] ?? null;
    $gradientClasses = \App\Helpers\CategoryColor::gradient($categorySlug, $categoryColor);
    $borderClasses = \App\Helpers\CategoryColor::border($categorySlug, $categoryColor);
@endphp

<article {{ $attributes->merge(['class' => 'group relative flex flex-col bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:-translate-y-1 hover:shadow-lg ' . ($featured ? 'md:flex-row' : '')]) }}>
    {{-- Cover image / gradient fallback --}}
    <a href="{{ route('post.show', $post['slug']) }}"
       class="{{ $featured ? 'md:w-2/5 shrink-0' : '' }} block relative overflow-hidden {{ $featured ? 'aspect-auto h-48 md:h-auto' : 'aspect-[16/9]' }}">
        @if(!empty($post['cover_image']))
            <img
                src="{{ $post['cover_image'] }}"
                alt="{{ $post['title'] }}"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
            >
        @else
            <div class="w-full h-full bg-gradient-to-br {{ $gradientClasses }} opacity-80 group-hover:opacity-90 transition-opacity duration-300 flex items-center justify-center">
                <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
        @endif
    </a>

    {{-- Content --}}
    <div class="flex flex-col flex-1 p-5 border-l-4 {{ $borderClasses }}">
        {{-- Categories --}}
        @if(!empty($post['categories']))
            <div class="flex flex-wrap gap-1.5 mb-2">
                @foreach($post['categories'] as $category)
                    <x-category-badge
                        :name="$category['name']"
                        :slug="$category['slug']"
                        :color="$category['color'] ?? null"
                        :href="route('category.show', $category['slug'])"
                    />
                @endforeach
            </div>
        @endif

        {{-- Title --}}
        <h3 class="{{ $featured ? 'text-xl' : 'text-base' }} font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
            <a href="{{ route('post.show', $post['slug']) }}" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors">
                {{ $post['title'] }}
            </a>
        </h3>

        {{-- Excerpt --}}
        @if(!empty($post['excerpt']))
            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3 flex-1">
                {{ $post['excerpt'] }}
            </p>
        @endif

        {{-- Meta row --}}
        <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-100 dark:border-gray-700">
            <time class="text-xs text-gray-400 dark:text-gray-500" datetime="{{ $post['published_at'] }}">
                {{ \Carbon\Carbon::parse($post['published_at'])->format('d.m.Y') }}
            </time>
            <div class="flex items-center gap-2">
                @if(!empty($post['tags']))
                    @foreach(array_slice($post['tags'], 0, 2) as $tag)
                        <a href="{{ route('tag.show', $tag['slug']) }}" class="text-xs text-gray-400 dark:text-gray-500 hover:text-sky-700 dark:hover:text-sky-400 transition-colors">
                            #{{ $tag['name'] }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</article>
