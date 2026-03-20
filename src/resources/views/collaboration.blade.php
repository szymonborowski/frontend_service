@extends('layouts.app')

@section('title', 'Collaboration')

@section('content')
    {{-- Page header --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-indigo-950 to-gray-900 dark:from-gray-950 dark:via-indigo-950 dark:to-gray-950 py-16 sm:py-20">
        <div class="absolute inset-0 bg-gradient-to-r from-sky-500/10 via-indigo-500/10 to-violet-500/10 animate-gradient"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-sky-400 font-mono text-sm mb-3 tracking-wide">> let's build something</p>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">Collaboration</h1>
            <p class="text-lg text-gray-300 max-w-2xl">
                Looking for a PHP developer to join your team, contribute to a project, or help you untangle a
                tricky codebase? Let's talk.
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-20">

        {{-- What I offer --}}
        <section class="opacity-0" x-data x-init="fadeInOnScroll($el)">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-8">What I Offer</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>',
                        'title' => 'PHP Development',
                        'desc'  => 'Custom module development, API integrations, legacy code migrations, and new feature implementation. Primarily Laravel and Magento, but adaptable to your stack.',
                    ],
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                        'title' => 'Code Review',
                        'desc'  => 'Structured review of existing codebases: identifying bottlenecks, security issues, dead code, and opportunities to improve readability and maintainability.',
                    ],
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>',
                        'title' => 'Consulting',
                        'desc'  => 'Architecture advice, technology selection, Docker / infrastructure setup, or just a second pair of eyes on a design decision you want to talk through.',
                    ],
                ] as $i => $service)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 opacity-0 stagger-{{ $i + 1 }}"
                         x-data x-init="fadeInOnScroll($el)">
                        <div class="w-10 h-10 rounded-lg bg-sky-50 dark:bg-sky-900/30 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $service['icon'] !!}
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $service['title'] }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $service['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- How it works --}}
        <section class="opacity-0" x-data x-init="fadeInOnScroll($el)">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-8">How It Works</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['step' => '01', 'title' => 'Get in touch',     'desc' => 'Send me a message describing your project or the problem you want to solve.'],
                    ['step' => '02', 'title' => 'Discovery call',   'desc' => 'We schedule a short call to align on scope, timeline, and expectations.'],
                    ['step' => '03', 'title' => 'Proposal',         'desc' => 'I send a written proposal with scope, deliverables, and an estimated effort.'],
                    ['step' => '04', 'title' => 'Let\'s build',     'desc' => 'Once we agree, we get to work with regular check-ins and transparent progress updates.'],
                ] as $step)
                    <div class="relative bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <span class="text-4xl font-bold text-gray-100 dark:text-gray-700 absolute top-4 right-5 select-none">{{ $step['step'] }}</span>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 relative">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed relative">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- CTA --}}
        <section class="opacity-0" x-data x-init="fadeInOnScroll($el)">
            <div class="bg-gradient-to-r from-sky-600 to-indigo-600 rounded-2xl p-10 text-center">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Ready to work together?</h2>
                <p class="text-sky-100 mb-8 max-w-xl mx-auto">
                    Drop me a message and let's figure out how I can help you ship better software.
                </p>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 px-8 py-3 rounded-lg bg-white text-sky-700 font-semibold text-sm hover:bg-sky-50 transition-colors shadow-lg">
                    Contact Me
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </section>

    </div>
@endsection
