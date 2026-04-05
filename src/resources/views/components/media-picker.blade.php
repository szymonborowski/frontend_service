<div
    x-data="mediaPicker()"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
    style="display: none"
    @click.self="close()"
    @open-media-picker.window="openPicker()"
    @keydown.escape.window="close()"
>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-3xl max-h-[80vh] flex flex-col" @click.stop>
        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('posts.insert_image') }}</h2>
            <button @click="close()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Search --}}
        <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700">
            <input
                type="search"
                x-model.debounce.400ms="search"
                @input="page = 1; loadMedia()"
                placeholder="{{ __('posts.search_media') }}"
                class="w-full max-w-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm focus:ring-sky-700 focus:border-sky-700"
            >
        </div>

        {{-- Grid --}}
        <div class="flex-1 overflow-y-auto px-5 py-4">
            <div x-show="loading" class="py-8 text-center text-gray-400">Loading...</div>
            <div x-show="!loading && items.length === 0" class="py-8 text-center text-gray-400">{{ __('posts.no_media_found') }}</div>
            <div x-show="!loading && items.length > 0" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                <template x-for="item in items" :key="item.id">
                    <button
                        @click="selectMedia(item)"
                        type="button"
                        class="relative aspect-square rounded-lg overflow-hidden border-2 border-transparent hover:border-sky-600 dark:hover:border-sky-400 transition cursor-pointer bg-gray-100 dark:bg-gray-900"
                    >
                        <img
                            :src="item.variant_urls?.thumbnail || item.url"
                            :alt="item.alt || item.filename"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                        <div class="absolute bottom-0 inset-x-0 px-1.5 py-1 text-xs text-white truncate" style="background:rgba(0,0,0,0.6)" x-text="item.filename"></div>
                    </button>
                </template>
            </div>
        </div>

        {{-- Pagination --}}
        <div x-show="lastPage > 1" class="flex items-center justify-between px-5 py-3 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-500">
            <span>Page <span x-text="page"></span> of <span x-text="lastPage"></span></span>
            <div class="flex gap-2">
                <button @click="page--; loadMedia()" :disabled="page <= 1"
                    class="px-3 py-1 rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 disabled:opacity-40">
                    Previous
                </button>
                <button @click="page++; loadMedia()" :disabled="page >= lastPage"
                    class="px-3 py-1 rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 disabled:opacity-40">
                    Next
                </button>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end">
            <button @click="close()" class="px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                {{ __('general.cancel') }}
            </button>
        </div>
    </div>
</div>

<script>
    function mediaPicker() {
        return {
            open: false,
            loading: false,
            items: [],
            search: '',
            page: 1,
            lastPage: 1,

            openPicker() {
                this.open = true;
                this.search = '';
                this.page = 1;
                this.loadMedia();
            },

            close() {
                this.open = false;
            },

            async loadMedia() {
                this.loading = true;
                try {
                    const params = new URLSearchParams({ page: this.page });
                    if (this.search) params.set('search', this.search);
                    const res = await fetch(`/panel/media?${params}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    });
                    const json = await res.json();
                    this.items = json.data || [];
                    this.lastPage = json.meta?.last_page || 1;
                } catch (e) {
                    this.items = [];
                } finally {
                    this.loading = false;
                }
            },

            selectMedia(item) {
                const alt = item.alt || item.filename;
                const url = item.variant_urls?.large || item.url;
                window.dispatchEvent(new CustomEvent('insert-markdown-image', { detail: { alt, url } }));
                this.close();
            },
        };
    }
</script>
