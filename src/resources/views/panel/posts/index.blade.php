@extends('layouts.panel')

@section('title', __('panel.my_posts_panel'))

@section('panel-title', __('panel.my_posts'))

@section('panel-content')
    <div class="mb-6">
        <a href="{{ route('panel.posts.create') }}"
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sky-800 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ __('posts.add_new_post') }}
        </a>
    </div>

    @php
        $postsList = $posts['data'] ?? [];
    @endphp

    @forelse($postsList as $post)
        <div class="border-b border-gray-200 py-4 last:border-b-0">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900">{{ $post['title'] }}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        @if(isset($post['slug']))
                            <span class="font-mono text-xs bg-gray-100 px-1 rounded">{{ $post['slug'] }}</span>
                        @endif
                    </p>
                    <p class="text-sm text-gray-600 mt-2">
                        {{ \Illuminate\Support\Str::limit(strip_tags($post['content'] ?? ''), 150) }}
                    </p>
                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                        <span>
                            {{ __('general.status') }}:
                            @if(($post['status'] ?? 'draft') === 'published')
                                <span class="text-green-600 font-medium">{{ __('posts.published') }}</span>
                            @else
                                <span class="text-yellow-600 font-medium">{{ __('posts.draft') }}</span>
                            @endif
                        </span>
                        @if(isset($post['created_at']))
                            <span>{{ __('general.created_at') }}: {{ \Carbon\Carbon::parse($post['created_at'])->format('d.m.Y H:i') }}</span>
                        @endif
                        @if(isset($post['updated_at']) && $post['updated_at'] !== $post['created_at'])
                            <span>{{ __('general.updated_at') }}: {{ \Carbon\Carbon::parse($post['updated_at'])->format('d.m.Y H:i') }}</span>
                        @endif
                    </div>
                    @if(!empty($post['categories']))
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach($post['categories'] as $category)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $category['name'] ?? $category }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($post['tags']))
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($post['tags'] as $tag)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                    #{{ $tag['name'] ?? $tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="flex items-center space-x-3 ml-4">
                    <a href="{{ route('panel.posts.edit', $post['id']) }}"
                       class="text-sm text-sky-800 hover:text-sky-600 font-medium">
                        {{ __('general.edit') }}
                    </a>
                    <form method="POST" action="{{ route('panel.posts.delete', $post['id']) }}"
                          onsubmit="return confirm('{{ __('posts.confirm_delete') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">
                            {{ __('general.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('posts.no_posts') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('posts.no_posts_yet') }}</p>
            <div class="mt-6">
                <a href="{{ route('panel.posts.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sky-800 hover:bg-sky-700">
                    {{ __('posts.create_first_post') }}
                </a>
            </div>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if(isset($posts['meta']['last_page']) && $posts['meta']['last_page'] > 1)
        <div class="mt-6 flex justify-center">
            {{-- Simple pagination links --}}
        </div>
    @endif
@endsection
