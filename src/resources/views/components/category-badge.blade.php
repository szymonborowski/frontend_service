@props(['name', 'href' => null, 'color' => null])

@php
    $colorClasses = \App\Helpers\CategoryColor::badge($color);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors hover:opacity-80 {$colorClasses}"]) }}>
        {{ $name }}
    </a>
@else
    <span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$colorClasses}"]) }}>
        {{ $name }}
    </span>
@endif
