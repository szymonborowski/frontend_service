@extends('layouts.app')

@section('title', __('general.landing_page_title'))

@section('og_description', __('general.landing_og_description'))

@section('content')

    {{-- =====================================================================
         HERO
         ===================================================================== --}}
    @php
        $portfolioSkills = [
            ['name' => __('general.landing_skill_apps'),       'category' => 'architecture', 'tag' => null, 'tier' => 'xl', 'top' => '20%', 'left' => '30%'],
            ['name' => __('general.landing_skill_automation'), 'category' => 'ai',           'tag' => null, 'tier' => 'lg', 'top' => '5%',  'left' => '55%'],
            ['name' => __('general.landing_skill_ai'),         'category' => 'ai',           'tag' => null, 'tier' => 'md', 'top' => '45%', 'left' => '70%'],
            ['name' => __('general.landing_skill_crm'),        'category' => 'backend',      'tag' => null, 'tier' => 'md', 'top' => '62%', 'left' => '18%'],
            ['name' => __('general.landing_skill_api'),        'category' => 'devops',       'tag' => null, 'tier' => 'sm', 'top' => '78%', 'left' => '55%'],
            ['name' => __('general.landing_skill_ecommerce'),  'category' => 'backend',      'tag' => null, 'tier' => 'sm', 'top' => '90%', 'left' => '32%'],
        ];
    @endphp

    <x-hero-section
        :greeting="__('general.landing_greeting')"
        :tagline="__('general.landing_tagline')"
        :subtitle="__('general.landing_subtitle')"
        :skills="$portfolioSkills"
    >
        <x-slot:title>
            <span class="text-gray-800 dark:text-white">Szymon</span>
            <span class="block text-gradient bg-gradient-to-r from-sky-500 via-indigo-500 to-violet-500 dark:from-sky-400 dark:via-indigo-400 dark:to-violet-400">Borowski</span>
        </x-slot>

        <x-slot:buttons>
            <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-chat'))"
                    class="inline-flex items-center px-6 py-3 rounded-lg bg-rose-700 hover:bg-rose-600 text-white font-medium transition-colors shadow-lg shadow-rose-700/30">
                <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                {{ __('general.hero_chat_cta') }}
            </button>
            <a href="#contact"
               class="inline-flex items-center px-6 py-3 rounded-lg bg-sky-600 hover:bg-sky-500 text-white font-medium transition-colors shadow-lg shadow-sky-600/30">
                {{ __('general.landing_contact_cta') }}
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </a>
            <a href="{{ config('app.blog_frontend_url') }}"
               class="inline-flex items-center px-6 py-3 rounded-lg bg-gray-800/10 hover:bg-gray-800/20 text-gray-700 dark:bg-white/10 dark:hover:bg-white/20 dark:text-white font-medium transition-colors border border-gray-800/20 dark:border-white/20 backdrop-blur-sm">
                {{ __('general.landing_go_to_blog') }}
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </x-slot>
    </x-hero-section>

    {{-- STATS BAND --}}
    <section class="bg-gray-100 dark:bg-gray-900 border-y border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="opacity-0 stagger-1" x-data x-init="fadeInOnScroll($el)">
                    <p class="text-sky-600 dark:text-sky-400 font-mono text-4xl font-bold leading-none">5+</p>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">{!! __('general.landing_stats_years') !!}</p>
                </div>
                <div class="opacity-0 stagger-2" x-data x-init="fadeInOnScroll($el)">
                    <p class="text-indigo-600 dark:text-indigo-400 font-mono text-4xl font-bold leading-none">20+</p>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">{!! __('general.landing_stats_projects') !!}</p>
                </div>
                <div class="opacity-0 stagger-3" x-data x-init="fadeInOnScroll($el)">
                    <p class="text-violet-600 dark:text-violet-400 font-mono text-4xl font-bold leading-none">3</p>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">{!! __('general.landing_stats_k8s') !!}</p>
                </div>
                <div class="opacity-0 stagger-4" x-data x-init="fadeInOnScroll($el)">
                    <p class="text-sky-600 dark:text-sky-400 font-mono text-4xl font-bold leading-none">24h</p>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">{!! __('general.landing_stats_response') !!}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         SERVICES
         ===================================================================== --}}
    <section id="uslugi" class="py-20 bg-gray-50 dark:bg-gray-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-14 opacity-0" x-data x-init="fadeInOnScroll($el)">
                <p class="text-sky-600 dark:text-sky-400 font-mono text-sm mb-2 tracking-wide">{{ __('general.landing_services_label') }}</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-white">{{ __('general.landing_services_title') }}</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <div class="opacity-0 stagger-1" x-data x-init="fadeInOnScroll($el)">
                    <div class="h-full bg-white dark:bg-gray-900 rounded-2xl p-8 border border-gray-200 dark:border-gray-800 hover:border-sky-300 dark:hover:border-sky-700 transition-colors group">
                        <div class="w-12 h-12 rounded-xl bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-3">
                            {{ __('general.landing_service_ai_title') }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ __('general.landing_service_ai_desc') }}
                        </p>
                    </div>
                </div>

                <div class="opacity-0 stagger-2" x-data x-init="fadeInOnScroll($el)">
                    <div class="h-full bg-white dark:bg-gray-900 rounded-2xl p-8 border border-gray-200 dark:border-gray-800 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors group">
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-3">
                            {{ __('general.landing_service_backend_title') }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ __('general.landing_service_backend_desc') }}
                        </p>
                    </div>
                </div>

                <div class="opacity-0 stagger-3" x-data x-init="fadeInOnScroll($el)">
                    <div class="h-full bg-white dark:bg-gray-900 rounded-2xl p-8 border border-gray-200 dark:border-gray-800 hover:border-violet-300 dark:hover:border-violet-700 transition-colors group">
                        <div class="w-12 h-12 rounded-xl bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-3">
                            {{ __('general.landing_service_devops_title') }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ __('general.landing_service_devops_desc') }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- =====================================================================
         DLACZEGO JA
         ===================================================================== --}}
    <section id="dlaczego-ja" class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-14 opacity-0" x-data x-init="fadeInOnScroll($el)">
                <p class="text-sky-600 dark:text-sky-400 font-mono text-sm mb-2 tracking-wide">{{ __('general.landing_why_label') }}</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-white">{{ __('general.landing_why_title') }}</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="opacity-0 stagger-1" x-data x-init="fadeInOnScroll($el)">
                    <div class="h-full bg-white dark:bg-gray-800 rounded-r-xl border-l-4 border-sky-500 pl-6 pr-6 py-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 rounded-lg bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">{{ __('general.landing_why_fast_title') }}</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ __('general.landing_why_fast_desc') }}
                        </p>
                    </div>
                </div>

                <div class="opacity-0 stagger-2" x-data x-init="fadeInOnScroll($el)">
                    <div class="h-full bg-white dark:bg-gray-800 rounded-r-xl border-l-4 border-indigo-500 pl-6 pr-6 py-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">{{ __('general.landing_why_fullstack_title') }}</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ __('general.landing_why_fullstack_desc') }}
                        </p>
                    </div>
                </div>

                <div class="opacity-0 stagger-3" x-data x-init="fadeInOnScroll($el)">
                    <div class="h-full bg-white dark:bg-gray-800 rounded-r-xl border-l-4 border-violet-500 pl-6 pr-6 py-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 rounded-lg bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">{{ __('general.landing_why_comms_title') }}</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ __('general.landing_why_comms_desc') }}
                        </p>
                    </div>
                </div>

            </div>

            <div class="mt-10 text-center opacity-0" x-data x-init="fadeInOnScroll($el)">
                <a href="{{ route('collaboration') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-sky-600 text-sky-600 dark:text-sky-400 dark:border-sky-500 hover:bg-sky-50 dark:hover:bg-sky-900/20 font-medium transition-colors">
                    {{ __('general.collab_cta_link') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- =====================================================================
         BLOG PREVIEW
         ===================================================================== --}}
    @if(!empty($featuredPosts))
    <section class="py-20 bg-gray-50 dark:bg-gray-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-end justify-between mb-10 opacity-0" x-data x-init="fadeInOnScroll($el)">
                <div>
                    <p class="text-sky-600 dark:text-sky-400 font-mono text-sm mb-2 tracking-wide">{{ __('general.landing_blog_label') }}</p>
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ __('general.landing_blog_title') }}</h2>
                </div>
                <a href="{{ config('app.blog_frontend_url') }}"
                   class="text-sm font-medium text-sky-600 dark:text-sky-400 hover:text-sky-500 flex items-center gap-1">
                    {{ __('general.landing_blog_all') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredPosts as $index => $post)
                    <div class="opacity-0 stagger-{{ $index + 1 }}" x-data x-init="fadeInOnScroll($el)">
                        <x-post-card :post="$post" />
                    </div>
                @endforeach
            </div>

        </div>
    </section>
    @endif

    {{-- =====================================================================
         KONTAKT
         ===================================================================== --}}
    <section id="contact" class="py-20 bg-gradient-to-br from-gray-900 via-indigo-950 to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-14 opacity-0" x-data x-init="fadeInOnScroll($el)">
                <p class="text-sky-400 font-mono text-sm mb-2 tracking-wide">{{ __('general.landing_contact_label') }}</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-white">{{ __('general.landing_contact_title') }}</h2>
                <p class="mt-4 text-gray-400 max-w-xl mx-auto">
                    {{ __('general.landing_contact_desc') }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- Dane kontaktowe --}}
                <div class="opacity-0 stagger-1" x-data x-init="fadeInOnScroll($el)">
                    <ul class="space-y-5 text-sm">
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-sky-900/50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-400 mb-0.5">Email</p>
                                <a href="mailto:szymon@borowski.services" class="text-white hover:text-sky-400 transition-colors">szymon@borowski.services</a>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-sky-900/50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-400 mb-0.5">{{ __('general.landing_contact_phone') }}</p>
                                <a href="tel:+48509132087" class="text-white hover:text-sky-400 transition-colors">+48 509 132 087</a>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-sky-900/50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-sky-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-400 mb-0.5">LinkedIn</p>
                                <a href="https://www.linkedin.com/in/szymon-borowski-db84/" target="_blank" rel="noopener" class="text-white hover:text-sky-400 transition-colors">szymon-borowski-db84</a>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Formularz kontaktowy --}}
                <div class="lg:col-span-2 opacity-0 stagger-2" x-data x-init="fadeInOnScroll($el)">
                    <form id="landing-contact-form"
                          action="{{ route('contact.send') }}"
                          method="POST"
                          class="space-y-5"
                          x-data="{ sending: false, done: false, error: false }"
                          @submit.prevent="
                              sending = true; done = false; error = false;
                              fetch($el.action, {
                                  method: 'POST',
                                  headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                                  body: new FormData($el)
                              })
                              .then(r => r.json())
                              .then(d => { sending = false; if(d.success) { done = true; $el.reset(); } else { error = true; } })
                              .catch(() => { sending = false; error = true; });
                          ">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm text-gray-400 mb-1.5">{{ __('general.landing_contact_name') }}</label>
                                <input type="text" name="name" required
                                       class="w-full bg-gray-800/60 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-600 transition"
                                       placeholder="{{ __('general.landing_contact_name_ph') }}">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-1.5">Email</label>
                                <input type="email" name="email" required
                                       class="w-full bg-gray-800/60 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-600 transition"
                                       placeholder="{{ __('general.landing_contact_email_ph') }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1.5">{{ __('general.landing_contact_subject') }}</label>
                            <input type="text" name="subject" required
                                   class="w-full bg-gray-800/60 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-600 transition"
                                   placeholder="{{ __('general.landing_contact_subject_ph') }}">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1.5">{{ __('general.landing_contact_message') }}</label>
                            <textarea name="message" rows="5" required
                                      class="w-full bg-gray-800/60 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-600 transition resize-none"
                                      placeholder="{{ __('general.landing_contact_message_ph') }}"></textarea>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit"
                                    :disabled="sending"
                                    class="inline-flex items-center px-7 py-3 rounded-lg bg-sky-600 hover:bg-sky-500 disabled:opacity-50 text-white font-semibold transition-colors">
                                <span x-show="!sending">{{ __('general.landing_contact_send') }}</span>
                                <span x-show="sending" x-cloak>{{ __('general.landing_contact_sending') }}</span>
                            </button>
                            <p x-show="done" x-cloak class="text-sm text-emerald-400">{{ __('general.landing_contact_success') }}</p>
                            <p x-show="error" x-cloak class="text-sm text-red-400">{{ __('general.landing_contact_error') }}</p>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>

@endsection
