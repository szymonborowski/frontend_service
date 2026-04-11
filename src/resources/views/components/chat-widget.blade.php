@php
    $welcomeMsg   = __('chat.welcome');
    $errorMsg     = __('chat.rate_limited') . '|' . __('chat.error');
    $chatSendUrl  = route('chat.send');
    $chatClearUrl = route('chat.clear');
@endphp

<div
    x-data="{
        open: false,
        messages: [{ role: 'assistant', content: @js($welcomeMsg) }],
        input: '',
        loading: false,
        sendUrl: @js($chatSendUrl),
        clearUrl: @js($chatClearUrl),
        welcomeMsg: @js($welcomeMsg),
        errorMsg: @js(__('chat.error')),
        rateLimitMsg: @js(__('chat.rate_limited')),

        toggle() {
            this.open = !this.open;
            if (this.open) this.$nextTick(() => this.$refs.input && this.$refs.input.focus());
        },

        async send() {
            const text = this.input.trim();
            if (!text || this.loading) return;

            this.messages.push({ role: 'user', content: text });
            this.input = '';
            this.loading = true;
            this.$nextTick(() => this.scrollToBottom());

            try {
                const res = await window.axios.post(this.sendUrl, { message: text });
                this.messages.push({ role: 'assistant', content: res.data.reply });
            } catch (err) {
                const msg = err.response?.status === 429 ? this.rateLimitMsg : this.errorMsg;
                this.messages.push({ role: 'assistant', content: msg });
            } finally {
                this.loading = false;
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        async clear() {
            await window.axios.post(this.clearUrl);
            this.messages = [{ role: 'assistant', content: this.welcomeMsg }];
        },

        scrollToBottom() {
            const el = this.$refs.messages;
            if (el) el.scrollTop = el.scrollHeight;
        }
    }"
    @keydown.escape.window="open = false"
>
    {{-- Floating button --}}
    <button
        @click="toggle()"
        :aria-label="open ? @js(__('chat.close_label')) : @js(__('chat.open_label'))"
        class="fixed bottom-6 right-6 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-rose-800 text-white shadow-lg transition-all duration-200 hover:bg-rose-800 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-rose-700 focus:ring-offset-2 dark:bg-rose-700 dark:hover:bg-rose-600"
        :class="open ? 'scale-90 opacity-0 pointer-events-none' : 'scale-100 opacity-100'"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>

    {{-- Chat panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        style="display: none; height: 30rem"
        class="fixed bottom-6 right-6 z-50 flex w-80 sm:w-96 flex-col rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-900"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between rounded-t-2xl bg-rose-800 px-4 py-3 dark:bg-rose-800">
            <span class="text-sm font-semibold text-white">{{ __('chat.title') }}</span>
            <div class="flex items-center gap-2">
                <button
                    @click="clear()"
                    title="{{ __('chat.new_chat') }}"
                    class="rounded p-1 text-rose-300 transition-colors hover:text-white"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
                <button
                    @click="open = false"
                    class="rounded p-1 text-rose-300 transition-colors hover:text-white"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Messages --}}
        <div
            x-ref="messages"
            class="flex-1 overflow-y-auto px-4 py-3 space-y-3"
        >
            <template x-for="(msg, i) in messages" :key="i">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <template x-if="msg.role === 'assistant'">
                        <div
                            class="prose prose-sm dark:prose-invert max-w-[80%] rounded-2xl rounded-bl-sm bg-gray-100 px-3 py-2 text-gray-800 dark:bg-gray-800 dark:text-gray-100"
                            x-html="renderMarkdown(msg.content)"
                        ></div>
                    </template>
                    <template x-if="msg.role === 'user'">
                        <div
                            class="max-w-[75%] rounded-2xl rounded-br-sm bg-rose-800 px-3 py-2 text-sm text-white dark:bg-rose-700"
                            x-text="msg.content"
                        ></div>
                    </template>
                </div>
            </template>

            {{-- Loading indicator --}}
            <div x-show="loading" class="flex justify-start">
                <div class="flex items-center gap-1 rounded-2xl rounded-bl-sm bg-gray-100 px-3 py-2 dark:bg-gray-800">
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400 dark:bg-gray-500" style="animation-delay: 0ms"></span>
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400 dark:bg-gray-500" style="animation-delay: 150ms"></span>
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400 dark:bg-gray-500" style="animation-delay: 300ms"></span>
                </div>
            </div>
        </div>

        {{-- Input --}}
        <div class="border-t border-gray-200 px-3 py-2 dark:border-gray-700">
            <form @submit.prevent="send()" class="flex items-end gap-2">
                <textarea
                    x-ref="input"
                    x-model="input"
                    @keydown.enter.prevent.exact="send()"
                    :placeholder="@js(__('chat.placeholder'))"
                    :disabled="loading"
                    rows="1"
                    class="flex-1 resize-none rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-rose-600 focus:outline-none focus:ring-1 focus:ring-rose-600 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-500"
                    style="max-height: 6rem; overflow-y: auto"
                    @input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 96) + 'px'"
                ></textarea>
                <button
                    type="submit"
                    :disabled="loading || !input.trim()"
                    class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-rose-800 text-white transition-colors hover:bg-rose-800 disabled:opacity-40 disabled:cursor-not-allowed dark:bg-rose-700 dark:hover:bg-rose-600"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
