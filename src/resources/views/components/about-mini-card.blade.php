<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col justify-between']) }}>
    <div>
        <div class="flex items-center gap-4 mb-4">
            <img src="/images/me200x200.png" alt="Szymon Borowski" class="w-16 h-16 rounded-full ring-2 ring-gray-200 dark:ring-gray-700">
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-gray-100">Szymon Borowski</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Software Developer</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
            {{ __('general.hero_subtitle') }}
        </p>
    </div>
    <a href="{{ url('/about') }}" class="mt-4 inline-flex items-center text-sm font-medium text-sky-700 dark:text-sky-400 hover:text-sky-600 dark:hover:text-sky-300 transition-colors">
        {{ __('general.hero_about_me') }}
        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>
