@extends('layouts.app')

@section('title', __('collaboration.title'))

@section('content')
    {{-- Page header --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-indigo-950 to-gray-900 dark:from-gray-950 dark:via-indigo-950 dark:to-gray-950 py-16 sm:py-20">
        <div class="absolute inset-0 bg-gradient-to-r from-sky-500/10 via-indigo-500/10 to-violet-500/10 animate-gradient"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-sky-400 font-mono text-sm mb-3 tracking-wide">{{ __('collaboration.subtitle') }}</p>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">{{ __('collaboration.heading') }}</h1>
            <p class="text-lg text-gray-300 max-w-2xl">
                {{ __('collaboration.intro') }}
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-20">

        {{-- What I offer --}}
        <section class="opacity-0" x-data x-init="fadeInOnScroll($el)">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-8">{{ __('collaboration.offer_heading') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>',
                        'title' => __('collaboration.offer_dev_title'),
                        'desc'  => __('collaboration.offer_dev_desc'),
                    ],
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                        'title' => __('collaboration.offer_review_title'),
                        'desc'  => __('collaboration.offer_review_desc'),
                    ],
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>',
                        'title' => __('collaboration.offer_consulting_title'),
                        'desc'  => __('collaboration.offer_consulting_desc'),
                    ],
                ] as $i => $service)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 opacity-0 stagger-{{ $i + 1 }}"
                         x-data x-init="fadeInOnScroll($el)">
                        <div class="w-10 h-10 rounded-lg bg-sky-50 dark:bg-sky-900/30 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $service['icon'] !!}
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $service['title'] }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $service['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- How it works --}}
        <section class="opacity-0" x-data x-init="fadeInOnScroll($el)">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-8">{{ __('collaboration.process_heading') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['step' => '01', 'title' => __('collaboration.step1_title'), 'desc' => __('collaboration.step1_desc')],
                    ['step' => '02', 'title' => __('collaboration.step2_title'), 'desc' => __('collaboration.step2_desc')],
                    ['step' => '03', 'title' => __('collaboration.step3_title'), 'desc' => __('collaboration.step3_desc')],
                    ['step' => '04', 'title' => __('collaboration.step4_title'), 'desc' => __('collaboration.step4_desc')],
                ] as $step)
                    <div class="relative bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <span class="text-4xl font-bold text-gray-200/70 dark:text-gray-700 absolute top-4 right-5 select-none">{{ $step['step'] }}</span>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2 relative">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed relative">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- For whom --}}
        <section class="opacity-0" x-data x-init="fadeInOnScroll($el)">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3">{{ __('collaboration.for_whom_heading') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-8">{{ __('collaboration.for_whom_intro') }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['title' => __('collaboration.for_whom_legal_title'),      'desc' => __('collaboration.for_whom_legal_desc'),      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
                    ['title' => __('collaboration.for_whom_agency_title'),     'desc' => __('collaboration.for_whom_agency_desc'),     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                    ['title' => __('collaboration.for_whom_ecommerce_title'),  'desc' => __('collaboration.for_whom_ecommerce_desc'),  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>'],
                    ['title' => __('collaboration.for_whom_operations_title'), 'desc' => __('collaboration.for_whom_operations_desc'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l1.5 1M13 16l2-4m0 0l3 4m-3-4h3m0 0V9a1 1 0 011-1h.5"/>'],
                ] as $i => $item)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 opacity-0 stagger-{{ $i + 1 }}"
                         x-data x-init="fadeInOnScroll($el)">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $item['icon'] !!}
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $item['title'] }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- After the project --}}
        <section class="opacity-0" x-data x-init="fadeInOnScroll($el)">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3">{{ __('collaboration.after_heading') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-8">{{ __('collaboration.after_intro') }}</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['title' => __('collaboration.after_warranty_title'),    'desc' => __('collaboration.after_warranty_desc'),    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'],
                    ['title' => __('collaboration.after_maintenance_title'), 'desc' => __('collaboration.after_maintenance_desc'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'],
                    ['title' => __('collaboration.after_handover_title'),    'desc' => __('collaboration.after_handover_desc'),    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>'],
                ] as $i => $item)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 opacity-0 stagger-{{ $i + 1 }}"
                         x-data x-init="fadeInOnScroll($el)">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $item['icon'] !!}
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $item['title'] }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- FAQ --}}
        <section class="opacity-0" x-data x-init="fadeInOnScroll($el)">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-8">{{ __('collaboration.faq_heading') }}</h2>
            <div class="max-w-3xl space-y-3">
                @foreach([
                    ['q' => __('collaboration.faq_q1'), 'a' => __('collaboration.faq_a1')],
                    ['q' => __('collaboration.faq_q2'), 'a' => __('collaboration.faq_a2')],
                    ['q' => __('collaboration.faq_q3'), 'a' => __('collaboration.faq_a3')],
                    ['q' => __('collaboration.faq_q4'), 'a' => __('collaboration.faq_a4')],
                    ['q' => __('collaboration.faq_q5'), 'a' => __('collaboration.faq_a5')],
                ] as $faq)
                    <div x-data="{ open: false }"
                         class="bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <button @click="open = !open"
                                class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left">
                            <span class="font-medium text-gray-800 dark:text-gray-100">{{ $faq['q'] }}</span>
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200"
                                 :class="open ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition-all duration-200 ease-out"
                             x-transition:enter-start="opacity-0 max-h-0"
                             x-transition:enter-end="opacity-100 max-h-96"
                             x-transition:leave="transition-all duration-150 ease-in"
                             x-transition:leave-start="opacity-100 max-h-96"
                             x-transition:leave-end="opacity-0 max-h-0"
                             class="overflow-hidden">
                            <p class="px-6 pb-5 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- CTA --}}
        <section class="opacity-0" x-data x-init="fadeInOnScroll($el)">
            <div class="bg-gradient-to-r from-sky-600 to-indigo-600 rounded-2xl p-10 text-center">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">{{ __('collaboration.cta_heading') }}</h2>
                <p class="text-sky-100 mb-8 max-w-xl mx-auto">
                    {{ __('collaboration.cta_text') }}
                </p>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 px-8 py-3 rounded-lg bg-white text-sky-700 font-semibold text-sm hover:bg-sky-50 transition-colors shadow-lg">
                    {{ __('collaboration.cta_button') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </section>

    </div>
@endsection
