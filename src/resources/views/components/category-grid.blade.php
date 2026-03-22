@props(['categories'])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6']) }}>
    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('general.categories') }}</h2>
    <div class="grid grid-cols-2 gap-2">
        @foreach($categories as $category)
            @php
                $colorClasses = \App\Helpers\CategoryColor::badge($category['color'] ?? null);
            @endphp
            <a href="{{ route('category.show', $category['slug']) }}"
               class="flex items-center justify-between px-3 py-2.5 rounded-lg {{ $colorClasses }} transition-all hover:opacity-80 hover:scale-[1.02]">
                <span class="text-sm font-medium truncate">{{ $category['name'] }}</span>
                <span class="text-xs opacity-70 ml-1 shrink-0">{{ $category['posts_count'] ?? 0 }}</span>
            </a>
        @endforeach
    </div>
</div>
