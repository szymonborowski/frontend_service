@extends('layouts.panel')

@section('title', __('panel.analytics_panel'))

@section('panel-title', __('panel.analytics'))

@section('panel-content')
    {{-- Summary cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-sky-50 rounded-lg p-5 flex items-center gap-4">
            <div class="p-3 bg-sky-100 rounded-full">
                <svg class="w-6 h-6 text-sky-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Łączne wyświetlenia</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($totalViews) }}</p>
            </div>
        </div>

        <div class="bg-sky-50 rounded-lg p-5 flex items-center gap-4">
            <div class="p-3 bg-sky-100 rounded-full">
                <svg class="w-6 h-6 text-sky-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Unikalni czytelnicy</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($uniqueViewers) }}</p>
            </div>
        </div>
    </div>

    {{-- Posts table --}}
    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Wyświetlenia per post</h3>

    @forelse($postList as $post)
        <div class="border-b border-gray-100 py-3 last:border-0 flex items-center justify-between gap-4">
            <div class="flex-1 min-w-0">
                <a href="{{ route('post.show', $post['slug']) }}"
                   class="text-sm font-medium text-gray-800 hover:text-sky-800 line-clamp-1">
                    {{ $post['title'] }}
                </a>
                <p class="text-xs text-gray-400 mt-0.5">
                    @if(($post['status'] ?? '') === 'published')
                        <span class="text-green-600">opublikowany</span>
                    @else
                        <span class="text-yellow-600">szkic</span>
                    @endif
                    @if(isset($post['published_at']))
                        · {{ \Carbon\Carbon::parse($post['published_at'])->format('d.m.Y') }}
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-1.5 text-sm font-semibold text-gray-700 whitespace-nowrap">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                {{ number_format($post['views']) }}
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <p class="text-sm text-gray-500">Nie masz jeszcze żadnych postów.</p>
            <a href="{{ route('panel.posts.create') }}"
               class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-sky-800 hover:bg-sky-700">
                Napisz pierwszy post
            </a>
        </div>
    @endforelse
@endsection
