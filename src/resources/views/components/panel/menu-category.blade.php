@props(['title'])

<div {{ $attributes->merge(['class' => '']) }}>
    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
        {{ $title }}
    </h3>
    <ul class="space-y-1">
        {{ $slot }}
    </ul>
</div>
