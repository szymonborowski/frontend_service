<?php

namespace App\Helpers;

class CategoryColor
{
    protected static array $colorMap = [
        'programming'     => 'violet',
        'technology'      => 'blue',
        'web-development' => 'emerald',
        'business'        => 'amber',
        'lifestyle'       => 'rose',
        'travel'          => 'cyan',
    ];

    // Badge classes: bg + text (light & dark)
    // These strings MUST stay here so Tailwind can scan them via @source
    protected static array $badge = [
        'violet'  => 'bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-300',
        'blue'    => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300',
        'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300',
        'amber'   => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300',
        'rose'    => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300',
        'cyan'    => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-500/20 dark:text-cyan-300',
        'gray'    => 'bg-gray-100 text-gray-700 dark:bg-gray-500/20 dark:text-gray-300',
    ];

    // Border color for card left-border accent
    protected static array $border = [
        'violet'  => 'border-l-violet-500',
        'blue'    => 'border-l-blue-500',
        'emerald' => 'border-l-emerald-500',
        'amber'   => 'border-l-amber-500',
        'rose'    => 'border-l-rose-500',
        'cyan'    => 'border-l-cyan-500',
        'gray'    => 'border-l-gray-500',
    ];

    // Gradient for cover image fallback
    protected static array $gradient = [
        'violet'  => 'from-violet-500 to-violet-700',
        'blue'    => 'from-blue-500 to-blue-700',
        'emerald' => 'from-emerald-500 to-emerald-700',
        'amber'   => 'from-amber-500 to-amber-700',
        'rose'    => 'from-rose-500 to-rose-700',
        'cyan'    => 'from-cyan-500 to-cyan-700',
        'gray'    => 'from-gray-500 to-gray-700',
    ];

    public static function name(string $slug, ?string $color = null): string
    {
        if ($color && isset(static::$badge[$color])) {
            return $color;
        }

        return static::$colorMap[$slug] ?? 'gray';
    }

    public static function badge(string $slug, ?string $color = null): string
    {
        return static::$badge[static::name($slug, $color)];
    }

    public static function border(string $slug, ?string $color = null): string
    {
        return static::$border[static::name($slug, $color)];
    }

    public static function gradient(string $slug, ?string $color = null): string
    {
        return static::$gradient[static::name($slug, $color)];
    }
}
