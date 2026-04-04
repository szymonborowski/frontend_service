<div
    x-data="{ open: false, src: '', alt: '' }"
    @open-lightbox.window="src = $event.detail.src; alt = $event.detail.alt; open = true"
    @keydown.escape.window="open = false"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
    style="display: none"
    @click.self="open = false"
>
    <button
        @click="open = false"
        class="absolute top-4 right-4 z-10 rounded-full p-2 text-white/70 hover:text-white transition-colors"
    >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
    </button>

    <img
        :src="src"
        :alt="alt"
        class="max-w-full max-h-full object-contain select-none"
        @click.stop
    >
</div>
