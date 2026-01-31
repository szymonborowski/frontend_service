@props(['href', 'active' => false, 'icon' => null])

@php
$classes = $active
    ? 'bg-sky-50 text-sky-800 border-l-4 border-sky-800'
    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-4 border-transparent';
@endphp

<li>
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "flex items-center px-3 py-2 text-sm font-medium rounded-r-md transition-colors $classes"]) }}>
        @if($icon)
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @switch($icon)
                    @case('user')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        @break
                    @case('posts')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        @break
                    @case('comments')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        @break
                    @case('plus')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        @break
                @endswitch
            </svg>
        @endif
        {{ $slot }}
    </a>
</li>
