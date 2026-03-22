<?php

namespace App\Helpers;

class CategoryColor
{
    // Badge classes: bg + text (light & dark)
    // These strings MUST stay here so Tailwind can scan them via @source
    protected static array $badge = [
        'slate'   => 'bg-slate-100 text-slate-700 dark:bg-slate-500/30 dark:text-slate-200',
        'red'     => 'bg-red-100 text-red-700 dark:bg-red-500/30 dark:text-red-200',
        'orange'  => 'bg-orange-100 text-orange-700 dark:bg-orange-500/30 dark:text-orange-200',
        'amber'   => 'bg-amber-100 text-amber-700 dark:bg-amber-500/30 dark:text-amber-200',
        'yellow'  => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/30 dark:text-yellow-200',
        'lime'    => 'bg-lime-100 text-lime-700 dark:bg-lime-500/30 dark:text-lime-200',
        'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/30 dark:text-emerald-200',
        'teal'    => 'bg-teal-100 text-teal-700 dark:bg-teal-500/30 dark:text-teal-200',
        'cyan'    => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-500/30 dark:text-cyan-200',
        'sky'     => 'bg-sky-100 text-sky-700 dark:bg-sky-500/30 dark:text-sky-200',
        'blue'    => 'bg-blue-100 text-blue-700 dark:bg-blue-500/30 dark:text-blue-200',
        'indigo'  => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/30 dark:text-indigo-200',
        'violet'  => 'bg-violet-100 text-violet-700 dark:bg-violet-500/30 dark:text-violet-200',
        'purple'  => 'bg-purple-100 text-purple-700 dark:bg-purple-500/30 dark:text-purple-200',
        'pink'    => 'bg-pink-100 text-pink-700 dark:bg-pink-500/30 dark:text-pink-200',
        'rose'    => 'bg-rose-100 text-rose-700 dark:bg-rose-500/30 dark:text-rose-200',
        'gray'    => 'bg-gray-100 text-gray-700 dark:bg-gray-500/30 dark:text-gray-200',
    ];

    // Border color for card left-border accent
    protected static array $border = [
        'slate'   => 'border-l-slate-500',
        'red'     => 'border-l-red-500',
        'orange'  => 'border-l-orange-500',
        'amber'   => 'border-l-amber-500',
        'yellow'  => 'border-l-yellow-500',
        'lime'    => 'border-l-lime-500',
        'emerald' => 'border-l-emerald-500',
        'teal'    => 'border-l-teal-500',
        'cyan'    => 'border-l-cyan-500',
        'sky'     => 'border-l-sky-500',
        'blue'    => 'border-l-blue-500',
        'indigo'  => 'border-l-indigo-500',
        'violet'  => 'border-l-violet-500',
        'purple'  => 'border-l-purple-500',
        'pink'    => 'border-l-pink-500',
        'rose'    => 'border-l-rose-500',
        'gray'    => 'border-l-gray-500',
    ];

    // Gradient for cover image fallback
    protected static array $gradient = [
        'slate'   => 'from-slate-500 to-slate-700',
        'red'     => 'from-red-500 to-red-700',
        'orange'  => 'from-orange-500 to-orange-700',
        'amber'   => 'from-amber-500 to-amber-700',
        'yellow'  => 'from-yellow-500 to-yellow-700',
        'lime'    => 'from-lime-500 to-lime-700',
        'emerald' => 'from-emerald-500 to-emerald-700',
        'teal'    => 'from-teal-500 to-teal-700',
        'cyan'    => 'from-cyan-500 to-cyan-700',
        'sky'     => 'from-sky-500 to-sky-700',
        'blue'    => 'from-blue-500 to-blue-700',
        'indigo'  => 'from-indigo-500 to-indigo-700',
        'violet'  => 'from-violet-500 to-violet-700',
        'purple'  => 'from-purple-500 to-purple-700',
        'pink'    => 'from-pink-500 to-pink-700',
        'rose'    => 'from-rose-500 to-rose-700',
        'gray'    => 'from-gray-500 to-gray-700',
    ];

    public static function badge(?string $color): string
    {
        return static::$badge[$color ?? 'gray'] ?? static::$badge['gray'];
    }

    public static function border(?string $color): string
    {
        return static::$border[$color ?? 'gray'] ?? static::$border['gray'];
    }

    public static function gradient(?string $color): string
    {
        return static::$gradient[$color ?? 'gray'] ?? static::$gradient['gray'];
    }
}
