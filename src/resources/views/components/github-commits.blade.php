@props(['commits', 'profileUrl'])

<div {{ $attributes->merge(['class' => 'bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col justify-between']) }}>
    <div>
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-gray-700 dark:text-gray-300 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/>
            </svg>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('general.recent_commits') }}</h2>
        </div>

        @if(!empty($commits))
            <ul class="space-y-3">
                @foreach($commits as $commit)
                    <li class="flex items-start gap-3">
                        <a href="{{ $commit['url'] }}"
                           target="_blank"
                           rel="noopener"
                           class="font-mono text-xs bg-gray-200/60 dark:bg-gray-700 text-sky-700 dark:text-sky-400 px-1.5 py-0.5 rounded shrink-0 hover:underline mt-0.5">
                            {{ $commit['short'] }}
                        </a>
                        <span class="text-sm text-gray-700 dark:text-gray-300 leading-snug line-clamp-2">
                            {{ $commit['message'] }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.no_commits') }}</p>
        @endif
    </div>

    <a href="{{ $profileUrl }}"
       target="_blank"
       rel="noopener"
       class="mt-4 inline-flex items-center text-sm font-medium text-sky-700 dark:text-sky-400 hover:text-sky-600 dark:hover:text-sky-300 transition-colors">
        {{ __('general.more') }}
        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>
