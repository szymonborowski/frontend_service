{{-- Hero Section — bold typography + journey artwork --}}
<section class="relative overflow-hidden bg-gradient-to-b from-slate-900 to-slate-950">
    {{-- Journey artwork (fit to height, centered, edge color fills sides) --}}
    <div class="absolute inset-0 dark:hidden" style="background-color: #f3f4f6;">
        <img src="/images/journey-light.webp"
             alt=""
             class="w-full h-full object-contain object-center opacity-25">
    </div>
    <div class="absolute inset-0 hidden dark:block" style="background-color: #0a0911;">
        <img src="/images/journey-dark.webp"
             alt=""
             class="w-full h-full object-contain object-center opacity-25">
    </div>

    {{-- Subtle grid pattern --}}
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23000000&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    {{-- Dark mode grid (white crosses) --}}
    <div class="absolute inset-0 opacity-0 dark:opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 lg:py-36">
        <div class="max-w-3xl">
            {{-- Greeting --}}
            <p class="text-sky-600 dark:text-sky-400 font-mono text-sm sm:text-base mb-4 tracking-wide">
                {{ __('general.hero_greeting') }}
            </p>

            {{-- Main heading with gradient text --}}
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                <span class="text-gray-800 dark:text-white">Extended</span><span class="text-gray-400 dark:text-gray-500">\</span><br class="sm:hidden">
                <span class="text-gradient bg-gradient-to-r from-sky-500 via-indigo-500 to-violet-500 dark:from-sky-400 dark:via-indigo-400 dark:to-violet-400">Mind<span class="text-sky-600 dark:text-sky-400">::</span>Thesis</span><span class="text-gray-400 dark:text-gray-500">()</span>
            </h1>

            {{-- Subtitle --}}
            <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-2xl leading-relaxed">
                {{ __('general.hero_subtitle') }}
            </p>

            {{-- Tech stack badges --}}
            <div class="flex flex-wrap gap-2 mb-10">
                @foreach(['PHP', 'Laravel', 'Docker', 'JavaScript', 'Tailwind CSS', 'MySQL'] as $tech)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-800/10 text-gray-600 border border-gray-800/10 dark:bg-white/10 dark:text-gray-300 dark:border-white/10 backdrop-blur-sm">
                        {{ $tech }}
                    </span>
                @endforeach
            </div>

            {{-- CTA buttons --}}
            <div class="flex flex-wrap gap-4">
                <a href="#posts" class="inline-flex items-center px-6 py-3 rounded-lg bg-sky-600 hover:bg-sky-500 text-white font-medium transition-colors shadow-lg shadow-sky-600/25">
                    {{ __('general.hero_read_blog') }}
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </a>
                <a href="{{ url('/about') }}" class="inline-flex items-center px-6 py-3 rounded-lg bg-gray-800/10 hover:bg-gray-800/20 text-gray-700 dark:bg-white/10 dark:hover:bg-white/20 dark:text-white font-medium transition-colors border border-gray-800/20 dark:border-white/20 backdrop-blur-sm">
                    {{ __('general.hero_about_me') }}
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ url('/collaboration') }}" class="inline-flex items-center px-6 py-3 rounded-lg bg-gray-800/10 hover:bg-gray-800/20 text-gray-700 dark:bg-white/10 dark:hover:bg-white/20 dark:text-white font-medium transition-colors border border-gray-800/20 dark:border-white/20 backdrop-blur-sm">
                    {{ __('general.hero_collaboration') }}
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
