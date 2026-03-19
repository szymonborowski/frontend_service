@props(['type' => 'info', 'message' => null])

@php
$classes = match($type) {
    'success' => 'bg-green-100 dark:bg-green-900/30 border-green-400 dark:border-green-600 text-green-700 dark:text-green-400',
    'error' => 'bg-red-100 dark:bg-red-900/30 border-red-400 dark:border-red-600 text-red-700 dark:text-red-400',
    'warning' => 'bg-yellow-100 dark:bg-yellow-900/30 border-yellow-400 dark:border-yellow-600 text-yellow-700 dark:text-yellow-400',
    default => 'bg-blue-100 dark:bg-blue-900/30 border-blue-400 dark:border-blue-600 text-blue-700 dark:text-blue-400',
};
@endphp

<div {{ $attributes->merge(['class' => "mb-4 p-3 border rounded $classes"]) }}>
    {{ $message ?? $slot }}
</div>
