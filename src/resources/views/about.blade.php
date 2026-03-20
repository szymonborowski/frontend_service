@extends('layouts.app')

@section('title', __('about.title'))

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-indigo-950 to-gray-900 dark:from-gray-950 dark:via-indigo-950 dark:to-gray-950 py-20 sm:py-28">
        <div class="absolute inset-0 bg-gradient-to-r from-sky-500/10 via-indigo-500/10 to-violet-500/10 animate-gradient"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center gap-8">
                <img src="/images/me400x400.png"
                     alt="Szymon Borowski"
                     class="w-32 h-32 sm:w-40 sm:h-40 rounded-full ring-4 ring-white/20 shadow-2xl shrink-0">
                <div>
                    <p class="text-sky-400 font-mono text-sm mb-2 tracking-wide">> whoami</p>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3">Szymon Borowski</h1>
                    <p class="text-lg text-gray-300">{!! __('about.subtitle') !!}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">

        {{-- Profile --}}
        <section class="max-w-3xl opacity-0" x-data x-init="fadeInOnScroll($el)">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ __('about.profile_heading') }}</h2>
            <div class="text-gray-600 dark:text-gray-400 space-y-4 text-base leading-relaxed">
                <p>{{ __('about.profile_p1') }}</p>
                <p>{{ __('about.profile_p2') }}</p>
            </div>
        </section>

        {{-- Skills --}}
        <section class="opacity-0" x-data x-init="fadeInOnScroll($el)">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ __('about.skills_heading') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach([
                    ['name' => 'PHP',                    'level' => __('about.level_expert'),       'years' => __('about.skill_years_10')],
                    ['name' => 'Magento 1 / 2',          'level' => __('about.level_expert'),       'years' => __('about.skill_years_8')],
                    ['name' => 'MySQL',                  'level' => __('about.level_advanced'),     'years' => __('about.skill_years_10')],
                    ['name' => 'Laravel',                'level' => __('about.level_intermediate'), 'years' => __('about.skill_growing')],
                    ['name' => 'REST API',               'level' => __('about.level_advanced'),     'years' => __('about.skill_years_10')],
                    ['name' => 'Docker',                 'level' => __('about.level_intermediate'), 'years' => __('about.skill_years_3')],
                    ['name' => 'HTML5 & CSS',            'level' => __('about.level_advanced'),     'years' => __('about.skill_years_10')],
                    ['name' => 'Responsive Web Design',  'level' => __('about.level_advanced'),     'years' => __('about.skill_daily')],
                    ['name' => 'Git',                    'level' => __('about.level_advanced'),     'years' => __('about.skill_years_8')],
                    ['name' => 'JavaScript',             'level' => __('about.level_basic'),        'years' => __('about.skill_proto_jquery')],
                    ['name' => 'Working with AI agents', 'level' => __('about.level_intermediate'), 'years' => __('about.skill_growing')],
                    ['name' => 'Claude Native Apps',     'level' => __('about.level_intermediate'), 'years' => __('about.skill_growing')],
                ] as $skill)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $skill['name'] }}</span>
                            <span class="text-xs font-medium text-sky-700 dark:text-sky-400">{{ $skill['level'] }}</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $skill['years'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Experience --}}
        <section class="opacity-0" x-data x-init="fadeInOnScroll($el)">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-8">{{ __('about.experience_heading') }}</h2>
            <div class="space-y-0">
                @foreach([
                    [
                        'title'   => __('about.job1_title'),
                        'company' => __('about.job1_company'),
                        'period'  => __('about.job1_period'),
                        'intro'   => __('about.job1_intro'),
                        'bullets' => __('about.job1_bullets'),
                        'current' => true,
                    ],
                    [
                        'title'   => __('about.job2_title'),
                        'company' => __('about.job2_company'),
                        'period'  => __('about.job2_period'),
                        'intro'   => null,
                        'bullets' => __('about.job2_bullets'),
                        'current' => false,
                    ],
                    [
                        'title'   => __('about.job3_title'),
                        'company' => __('about.job3_company'),
                        'period'  => __('about.job3_period'),
                        'intro'   => null,
                        'bullets' => __('about.job3_bullets'),
                        'current' => false,
                    ],
                    [
                        'title'   => __('about.job4_title'),
                        'company' => __('about.job4_company'),
                        'period'  => __('about.job4_period'),
                        'intro'   => null,
                        'bullets' => __('about.job4_bullets'),
                        'current' => false,
                    ],
                ] as $index => $job)
                    <div class="flex gap-5">
                        {{-- Timeline indicator --}}
                        <div class="flex flex-col items-center shrink-0">
                            <div class="w-3 h-3 rounded-full mt-1.5 shrink-0 {{ $job['current'] ? 'bg-sky-500 ring-4 ring-sky-500/20' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                            @if(!$loop->last)
                                <div class="flex-1 w-px bg-gray-200 dark:bg-gray-700 mt-1 mb-0"></div>
                            @endif
                        </div>
                        {{-- Content --}}
                        <div class="pb-10 {{ $loop->last ? 'pb-0' : '' }}">
                            <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1 mb-1">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $job['title'] }}</h3>
                                @if($job['current'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-sky-100 dark:bg-sky-900/40 text-sky-700 dark:text-sky-400">
                                        {{ __('about.present') }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm font-medium text-sky-700 dark:text-sky-400 mb-0.5">{{ $job['company'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ $job['period'] }}</p>
                            @if($job['intro'])
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $job['intro'] }}</p>
                            @endif
                            <ul class="space-y-1">
                                @foreach($job['bullets'] as $bullet)
                                    <li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="text-sky-500 mt-1.5 shrink-0">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                        </span>
                                        {{ $bullet }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Education + Languages --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 opacity-0" x-data x-init="fadeInOnScroll($el)">

            {{-- Education --}}
            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ __('about.education_heading') }}</h2>
                <div class="flex gap-5">
                    <div class="flex flex-col items-center shrink-0">
                        <div class="w-3 h-3 rounded-full bg-gray-300 dark:bg-gray-600 mt-1.5 shrink-0"></div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ __('about.edu1_school') }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('about.edu1_period') }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('about.edu1_degree') }}</p>
                    </div>
                </div>
            </section>

            {{-- Languages --}}
            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ __('about.languages_heading') }}</h2>
                <div class="space-y-3">
                    @foreach([
                        ['name' => __('about.lang_pl'), 'level' => __('about.lang_pl_level')],
                        ['name' => __('about.lang_en'), 'level' => __('about.lang_en_level')],
                    ] as $lang)
                        <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 px-5 py-3">
                            <span class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $lang['name'] }}</span>
                            <span class="text-xs font-medium text-sky-700 dark:text-sky-400">{{ $lang['level'] }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- CV download --}}
        <section class="opacity-0" x-data x-init="fadeInOnScroll($el)">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ __('about.cv_heading') }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('about.cv_subtitle') }}</p>
                </div>
                <div class="flex gap-3 shrink-0">
                    <a href="/resume/Szymon%20Borowski%20CV.pdf"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        CV PL
                    </a>
                    <a href="/resume/Szymon%20Borowski%20Resume.pdf"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-sky-600 hover:bg-sky-500 text-white text-sm font-medium transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        CV EN
                    </a>
                </div>
            </div>
        </section>

    </div>
@endsection

@section('pre-footer')
    <x-newsletter-cta />
@endsection
