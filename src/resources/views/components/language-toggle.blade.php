<div class="flex items-center gap-0.5">
    @foreach(['pl' => 'PL', 'en' => 'EN'] as $code => $label)
        @if(app()->getLocale() === $code)
            <span class="px-2 py-1 text-xs font-semibold rounded text-sky-700 dark:text-sky-400 bg-gray-100 dark:bg-gray-800">
                {{ $label }}
            </span>
        @else
            <a
                href="{{ route('lang.switch', $code) }}"
                class="px-2 py-1 text-xs font-medium rounded text-gray-500 dark:text-gray-400 hover:text-sky-700 dark:hover:text-sky-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            >
                {{ $label }}
            </a>
        @endif
    @endforeach
</div>
