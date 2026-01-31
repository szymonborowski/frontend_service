@extends('layouts.panel')

@section('title', __('panel.my_comments_panel'))

@section('panel-title', __('panel.my_comments'))

@section('panel-content')
    @php
        $commentsList = $comments['data'] ?? [];
    @endphp

    @forelse($commentsList as $comment)
        <div class="border-b border-gray-200 py-4 last:border-b-0">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-gray-800">{{ $comment['content'] }}</p>
                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                        @if(isset($comment['post']))
                            <span>
                                {{ __('comments.under_post') }}
                                <a href="{{ url('/posts/' . ($comment['post']['slug'] ?? $comment['post_id'])) }}"
                                   class="text-sky-800 hover:underline font-medium">
                                    {{ $comment['post']['title'] ?? 'Post #' . $comment['post_id'] }}
                                </a>
                            </span>
                        @endif
                        @if(isset($comment['created_at']))
                            <span>{{ \Carbon\Carbon::parse($comment['created_at'])->format('d.m.Y H:i') }}</span>
                        @endif
                        @if(isset($comment['status']))
                            <span>
                                {{ __('general.status') }}:
                                @switch($comment['status'])
                                    @case('approved')
                                        <span class="text-green-600">{{ __('comments.status_approved') }}</span>
                                        @break
                                    @case('pending')
                                        <span class="text-yellow-600">{{ __('comments.status_pending') }}</span>
                                        @break
                                    @case('rejected')
                                        <span class="text-red-600">{{ __('comments.status_rejected') }}</span>
                                        @break
                                    @default
                                        <span>{{ $comment['status'] }}</span>
                                @endswitch
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('comments.no_comments') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('comments.no_comments_yet') }}</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if(isset($comments['meta']['last_page']) && $comments['meta']['last_page'] > 1)
        <div class="mt-6 flex justify-center">
            {{-- Simple pagination links --}}
        </div>
    @endif
@endsection
