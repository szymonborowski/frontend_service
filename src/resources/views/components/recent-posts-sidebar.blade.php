@props(['recentPosts'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('general.recent_posts') }}</h2>
    <ul class="space-y-2">
        @forelse($recentPosts as $post)
            @php
                $firstCat = $post['categories'][0] ?? null;
                $colorClass = $firstCat
                    ? \App\Helpers\CategoryColor::badge($firstCat['slug'], $firstCat['color'] ?? null)
                    : 'bg-gray-100 text-gray-700 dark:bg-gray-500/30 dark:text-gray-200';
            @endphp
            <li>
                <a href="{{ route('post.show', $post['slug']) }}"
                   class="flex rounded-lg overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 group hover:shadow-md hover:-translate-y-0.5 transition-all duration-150">
                    <div class="w-14 shrink-0 {{ $colorClass }} flex items-center justify-center">
                        <span class="text-xl font-bold uppercase">
                            {{ mb_substr($firstCat['name'] ?? '?', 0, 1) }}
                        </span>
                    </div>
                    <div class="flex-1 px-3 py-2.5 bg-white dark:bg-gray-800">
                        <h3 class="text-xs font-medium text-gray-900 dark:text-gray-100 group-hover:text-sky-700 dark:group-hover:text-sky-400 line-clamp-2 leading-snug">
                            {{ $post['title'] }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ \Carbon\Carbon::parse($post['published_at'])->format('d.m.Y') }}
                        </p>
                    </div>
                </a>
            </li>
        @empty
            <li class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.no_posts') }}</li>
        @endforelse
    </ul>
</div>
