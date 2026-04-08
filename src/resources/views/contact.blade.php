@extends('layouts.app')

@section('title', __('contact.title'))

@section('content')
    {{-- Page header --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-indigo-950 to-gray-900 dark:from-gray-950 dark:via-indigo-950 dark:to-gray-950 py-16 sm:py-20">
        <div class="absolute inset-0 bg-gradient-to-r from-sky-500/10 via-indigo-500/10 to-violet-500/10 animate-gradient"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-sky-400 font-mono text-sm mb-3 tracking-wide">{{ __('contact.hero_prompt') }}</p>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">{{ __('contact.heading') }}</h1>
            <p class="text-lg text-gray-300 max-w-xl">{{ __('contact.lead') }}</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            {{-- Contact info --}}
            <div class="opacity-0" x-data x-init="fadeInOnScroll($el)">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">{{ __('contact.details_heading') }}</h2>
                <ul class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-sky-600 dark:text-sky-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-100 mb-0.5">{{ __('contact.label_email') }}</p>
                            <a href="mailto:szymon@borowski.services" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors">
                                szymon@borowski.services
                            </a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-sky-600 dark:text-sky-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-100 mb-0.5">{{ __('contact.label_phone') }}</p>
                            <a href="tel:+48509132087" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors">
                                +48 509 132 087
                            </a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-sky-600 dark:text-sky-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-100 mb-0.5">{{ __('contact.label_location') }}</p>
                            <span>{{ __('contact.location_value') }}</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-sky-600 dark:text-sky-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-100 mb-0.5">{{ __('contact.label_linkedin') }}</p>
                            <a href="https://www.linkedin.com/in/szymon-borowski-db84/" target="_blank" rel="noopener"
                               class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors">
                                szymon-borowski-db84
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Contact form --}}
            <div class="lg:col-span-2 opacity-0 stagger-1" x-data x-init="fadeInOnScroll($el)">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">{{ __('contact.form_heading') }}</h2>

                <div
                    x-data="{
                        name: '',
                        email: '',
                        phone: '',
                        subject: '',
                        message: '',
                        status: 'idle',
                        errorMsg: '',
                        fieldErrors: {},
                        async submit() {
                            this.status = 'loading';
                            this.errorMsg = '';
                            this.fieldErrors = {};
                            try {
                                const res = await fetch('{{ route('contact.send') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                        'Accept': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        name: this.name,
                                        email: this.email,
                                        phone: this.phone || null,
                                        subject: this.subject,
                                        message: this.message,
                                    }),
                                });
                                if (res.ok) {
                                    this.status = 'success';
                                } else if (res.status === 422) {
                                    const data = await res.json();
                                    this.fieldErrors = data.errors ?? {};
                                    this.status = 'error';
                                } else {
                                    this.errorMsg = '{{ __('contact.error_message') }}';
                                    this.status = 'error';
                                }
                            } catch (e) {
                                this.errorMsg = '{{ __('contact.error_message') }}';
                                this.status = 'error';
                            }
                        }
                    }"
                >
                    {{-- Success state --}}
                    <div x-show="status === 'success'" x-cloak
                         class="flex items-start gap-4 p-6 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700">
                        <svg class="w-6 h-6 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-emerald-800 dark:text-emerald-300 font-medium">{{ __('contact.success_message') }}</p>
                    </div>

                    {{-- Form --}}
                    <form @submit.prevent="submit" x-show="status !== 'success'" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    {{ __('contact.field_name') }}
                                </label>
                                <input type="text" id="name" name="name" x-model="name"
                                       placeholder="{{ __('contact.field_name_ph') }}"
                                       required
                                       :class="fieldErrors.name ? 'border-rose-400 focus:ring-rose-500' : 'border-gray-300 dark:border-gray-600 focus:ring-sky-500'"
                                       class="w-full px-4 py-2.5 rounded-lg border bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 transition text-sm">
                                <p x-show="fieldErrors.name" x-text="fieldErrors.name?.[0]" x-cloak class="mt-1 text-xs text-rose-500"></p>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    {{ __('contact.field_email') }}
                                </label>
                                <input type="email" id="email" name="email" x-model="email"
                                       placeholder="{{ __('contact.field_email_ph') }}"
                                       required
                                       :class="fieldErrors.email ? 'border-rose-400 focus:ring-rose-500' : 'border-gray-300 dark:border-gray-600 focus:ring-sky-500'"
                                       class="w-full px-4 py-2.5 rounded-lg border bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 transition text-sm">
                                <p x-show="fieldErrors.email" x-text="fieldErrors.email?.[0]" x-cloak class="mt-1 text-xs text-rose-500"></p>
                            </div>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                {{ __('contact.field_phone') }}
                            </label>
                            <input type="tel" id="phone" name="phone" x-model="phone"
                                   placeholder="{{ __('contact.field_phone_ph') }}"
                                   :class="fieldErrors.phone ? 'border-rose-400 focus:ring-rose-500' : 'border-gray-300 dark:border-gray-600 focus:ring-sky-500'"
                                   class="w-full px-4 py-2.5 rounded-lg border bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 transition text-sm">
                            <p x-show="fieldErrors.phone" x-text="fieldErrors.phone?.[0]" x-cloak class="mt-1 text-xs text-rose-500"></p>
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                {{ __('contact.field_subject') }}
                            </label>
                            <input type="text" id="subject" name="subject" x-model="subject"
                                   placeholder="{{ __('contact.field_subject_ph') }}"
                                   required
                                   :class="fieldErrors.subject ? 'border-rose-400 focus:ring-rose-500' : 'border-gray-300 dark:border-gray-600 focus:ring-sky-500'"
                                   class="w-full px-4 py-2.5 rounded-lg border bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 transition text-sm">
                            <p x-show="fieldErrors.subject" x-text="fieldErrors.subject?.[0]" x-cloak class="mt-1 text-xs text-rose-500"></p>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                {{ __('contact.field_message') }}
                            </label>
                            <textarea id="message" name="message" rows="6" x-model="message"
                                      placeholder="{{ __('contact.field_message_ph') }}"
                                      required
                                      :class="fieldErrors.message ? 'border-rose-400 focus:ring-rose-500' : 'border-gray-300 dark:border-gray-600 focus:ring-sky-500'"
                                      class="w-full px-4 py-2.5 rounded-lg border bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 transition text-sm resize-none"></textarea>
                            <p x-show="fieldErrors.message" x-text="fieldErrors.message?.[0]" x-cloak class="mt-1 text-xs text-rose-500"></p>
                        </div>

                        {{-- General error --}}
                        <div x-show="status === 'error'" x-cloak
                             class="p-4 rounded-lg bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-700 text-sm text-rose-700 dark:text-rose-400"
                             x-text="errorMsg">
                        </div>

                        <button type="submit"
                                :disabled="status === 'loading'"
                                class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-sky-600 hover:bg-sky-500 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-medium transition-colors">
                            <svg x-show="status === 'loading'" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            <span x-show="status !== 'loading'">{{ __('contact.btn_send') }}</span>
                            <span x-show="status === 'loading'" x-cloak>{{ __('contact.btn_sending') }}</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
