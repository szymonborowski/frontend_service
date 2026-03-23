@props(['categories'])

<div x-data="{ expanded: false }" {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col justify-between']) }}>
    <div>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('general.categories') }}</h2>
        <div class="relative">
            <div class="grid grid-cols-2 gap-2 overflow-hidden transition-[max-height] duration-300 ease-in-out"
                 :class="expanded ? 'max-h-[80rem]' : 'max-h-[11rem]'">
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
            <div x-show="!expanded"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-white dark:from-gray-800 to-transparent pointer-events-none"></div>
        </div>
    </div>

    <button @click="expanded = !expanded"
            class="mt-4 inline-flex items-center text-sm font-medium text-sky-700 dark:text-sky-400 hover:text-sky-600 dark:hover:text-sky-300 transition-colors cursor-pointer">
        <span x-text="expanded ? '{{ __('general.less') }}' : '{{ __('general.more') }}'"></span>
        <svg class="ml-1 w-4 h-4 transition-transform duration-300" :class="expanded ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
</div>
