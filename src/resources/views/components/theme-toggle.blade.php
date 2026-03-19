<div
    x-data="{
        theme: localStorage.getItem('theme') || 'auto',
        init() {
            this.apply();
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (this.theme === 'auto') this.apply();
            });
        },
        toggle() {
            this.theme = this.theme === 'auto' ? 'dark' : (this.theme === 'dark' ? 'light' : 'auto');
            localStorage.setItem('theme', this.theme);
            this.apply();
        },
        apply() {
            const isDark = this.theme === 'dark' ||
                (this.theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);
        }
    }"
>
    <button
        @click="toggle"
        class="relative p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
        :title="theme === 'auto' ? '{{ __('general.theme_auto') }}' : (theme === 'dark' ? '{{ __('general.theme_dark') }}' : '{{ __('general.theme_light') }}')"
    >
        {{-- Sun (light mode) --}}
        <svg x-show="theme === 'light'" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        {{-- Moon (dark mode) --}}
        <svg x-show="theme === 'dark'" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
        {{-- Monitor (auto mode) --}}
        <svg x-show="theme === 'auto'" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
    </button>
</div>
