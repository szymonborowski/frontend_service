@props(['type', 'id', 'count' => 0, 'liked' => false, 'size' => 'md'])

@php
    $sizeClasses = match($size) {
        'sm' => 'gap-1 text-xs',
        default => 'gap-1.5 text-sm',
    };
    $iconSize = match($size) {
        'sm' => 'w-4 h-4',
        default => 'w-5 h-5',
    };
@endphp

<div
    x-data="{
        liked: {{ $liked ? 'true' : 'false' }},
        count: {{ (int) $count }},
        loading: false,
        async toggle() {
            if (this.loading) return;
            this.loading = true;
            this.liked = !this.liked;
            this.count += this.liked ? 1 : -1;
            try {
                const res = await fetch('{{ route('likes.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ type: '{{ $type }}', id: '{{ $id }}' }),
                });
                if (res.ok) {
                    const data = await res.json();
                    this.liked = data.liked;
                    this.count = data.count;
                } else {
                    this.liked = !this.liked;
                    this.count += this.liked ? 1 : -1;
                }
            } catch (e) {
                this.liked = !this.liked;
                this.count += this.liked ? 1 : -1;
            }
            this.loading = false;
        }
    }"
    class="inline-flex items-center"
>
    <button
        @click="toggle()"
        :disabled="loading"
        class="inline-flex items-center {{ $sizeClasses }} rounded-full px-3 py-1.5 font-medium transition-all duration-200 border focus:outline-none focus:ring-2 focus:ring-sky-500/50"
        :class="liked
            ? 'bg-rose-50 dark:bg-rose-500/10 border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-500/20'
            : 'bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-rose-500 dark:hover:text-rose-400'"
        :title="liked ? '{{ __('general.likes') }}' : '{{ __('general.like') }}'"
    >
        {{-- Heart icon --}}
        <svg
            class="{{ $iconSize }} transition-transform duration-200"
            :class="liked ? 'scale-110' : ''"
            :fill="liked ? 'currentColor' : 'none'"
            stroke="currentColor"
            viewBox="0 0 24 24"
            stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
        </svg>
        <span x-text="count"></span>
    </button>
</div>
