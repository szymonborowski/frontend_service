{{-- Pre-footer Newsletter CTA --}}
@if(env('NEWSLETTER_ENABLED', false))
<section class="relative overflow-hidden bg-gradient-to-r from-gray-900 via-indigo-950 to-gray-900">
    {{-- Animated gradient overlay --}}
    <div class="absolute inset-0 bg-gradient-to-r from-sky-500/5 via-violet-500/10 to-sky-500/5 animate-gradient"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
            {{ __('general.newsletter_title') }}
        </h2>
        <p class="text-gray-300 text-lg mb-8 max-w-2xl mx-auto">
            {{ __('general.newsletter_subtitle') }}
        </p>

        {{-- Email form --}}
        <div
            x-data="{
                email: '',
                status: 'idle',
                message: '',
                async submit() {
                    if (!this.email) return;
                    this.status = 'loading';
                    try {
                        const res = await fetch('{{ route('newsletter.subscribe') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ email: this.email }),
                        });
                        const data = await res.json();
                        this.status = data.success ? 'success' : 'error';
                        this.message = data.message || (data.errors?.email?.[0] ?? '{{ __('general.newsletter_error') }}');
                    } catch (e) {
                        this.status = 'error';
                        this.message = '{{ __('general.newsletter_error') }}';
                    }
                }
            }"
        >
            <form @submit.prevent="submit" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto" x-show="status !== 'success'">
                <input
                    type="email"
                    x-model="email"
                    required
                    placeholder="{{ __('general.newsletter_placeholder') }}"
                    class="flex-1 px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent backdrop-blur-sm"
                >
                <button
                    type="submit"
                    :disabled="status === 'loading'"
                    class="px-6 py-3 rounded-lg bg-sky-600 hover:bg-sky-500 disabled:opacity-50 text-white font-medium transition-colors shadow-lg shadow-sky-600/25 whitespace-nowrap"
                >
                    <span x-show="status !== 'loading'">{{ __('general.newsletter_subscribe') }}</span>
                    <span x-show="status === 'loading'" x-cloak>...</span>
                </button>
            </form>

            {{-- Success message --}}
            <div x-show="status === 'success'" x-cloak class="text-emerald-400 font-medium text-lg" x-text="message"></div>

            {{-- Error message --}}
            <div x-show="status === 'error'" x-cloak class="text-rose-400 text-sm mt-2" x-text="message"></div>
        </div>

        {{-- Tech topic pills --}}
        <div class="flex flex-wrap justify-center gap-2 mt-8">
            @foreach(['#Laravel', '#PHP', '#Docker', '#Architecture', '#DevOps', '#Tailwind'] as $topic)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-gray-300 border border-white/10">
                    {{ $topic }}
                </span>
            @endforeach
        </div>
    </div>
</section>
@endif
