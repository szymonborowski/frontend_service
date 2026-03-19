@props(['tags'])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6']) }}>
    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('general.tags') }}</h2>
    <div class="flex flex-wrap gap-2">
        @foreach($tags as $tag)
            <a href="{{ route('tag.show', $tag['slug']) }}"
               class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30 hover:text-sky-700 dark:hover:text-sky-400 transition-all hover:scale-105">
                #{{ $tag['name'] }}
            </a>
        @endforeach
    </div>
</div>
