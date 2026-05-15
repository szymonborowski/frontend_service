@props(['skills' => null])

@php
    $skills ??= [
        ['name' => 'Microservices',    'category' => 'architecture', 'tag' => 'microservices', 'tier' => 'md', 'top' => '6%',  'left' => '60%'],
        ['name' => 'Anthropic Claude', 'category' => 'ai',           'tag' => 'anthropic',     'tier' => 'xl', 'top' => '24%', 'left' => '32%'],
        ['name' => 'AI Agents',        'category' => 'ai',           'tag' => 'agent',         'tier' => 'lg', 'top' => '42%', 'left' => '74%'],
        ['name' => 'Laravel',          'category' => 'backend',      'tag' => 'laravel',       'tier' => 'sm', 'top' => '54%', 'left' => '18%'],
        ['name' => 'Kubernetes',       'category' => 'devops',       'tag' => 'kubernetes',    'tier' => 'md', 'top' => '72%', 'left' => '55%'],
        ['name' => 'RAG',              'category' => 'ai',           'tag' => null,            'tier' => 'lg', 'top' => '88%', 'left' => '28%'],
    ];

    $tierClasses = [
        'xl' => 'text-lg sm:text-xl font-bold px-5 py-2',
        'lg' => 'text-base sm:text-lg font-semibold px-4 py-1.5',
        'md' => 'text-sm sm:text-base font-semibold px-3.5 py-1.5',
        'sm' => 'text-xs sm:text-sm font-medium px-3 py-1',
    ];

    $categoryClasses = [
        'ai'           => 'text-sky-700 dark:text-sky-200 border-sky-400/50 dark:border-sky-300/50 bg-gradient-to-r from-sky-500/10 via-indigo-500/10 to-violet-500/10 dark:from-sky-400/20 dark:via-indigo-400/20 dark:to-violet-400/20 shadow-[0_0_24px_-4px_rgba(99,102,241,0.45)]',
        'backend'      => 'text-violet-700 dark:text-violet-200 border-violet-500/40 dark:border-violet-300/40 bg-violet-500/10 dark:bg-violet-400/15',
        'devops'       => 'text-cyan-700 dark:text-cyan-200 border-cyan-500/40 dark:border-cyan-300/40 bg-cyan-500/10 dark:bg-cyan-400/15',
        'architecture' => 'text-indigo-700 dark:text-indigo-200 border-indigo-500/40 dark:border-indigo-300/40 bg-indigo-500/10 dark:bg-indigo-400/15',
    ];
@endphp

<div class="skill-cloud-cluster" role="list" aria-label="{{ __('general.hero_skills_label') }}">
    @foreach($skills as $i => $s)
        @php
            $tagClasses = 'inline-flex items-center rounded-full border backdrop-blur-sm transition-transform duration-200 hover:scale-110 hover:-translate-y-0.5 skill-drift skill-drift-' . ($i % 6 + 1)
                . ' ' . $tierClasses[$s['tier']]
                . ' ' . $categoryClasses[$s['category']];
        @endphp
        <div class="skill-pos" style="--skill-top: {{ $s['top'] }}; --skill-left: {{ $s['left'] }};">
            @if($s['tag'])
                <a href="{{ url('/tag/' . $s['tag']) }}"
                   class="{{ $tagClasses }}"
                   role="listitem"
                   aria-label="{{ $s['name'] }}">
                    {{ $s['name'] }}
                </a>
            @else
                <span class="{{ $tagClasses }}" role="listitem" aria-label="{{ $s['name'] }}">
                    {{ $s['name'] }}
                </span>
            @endif
        </div>
    @endforeach
</div>
