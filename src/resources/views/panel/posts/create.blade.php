@extends('layouts.panel')

@section('title', __('panel.new_post_panel'))

@section('panel-title', __('panel.new_post'))

@section('panel-content')
    <form method="POST" action="{{ route('panel.posts.store') }}" class="space-y-6">
        @csrf

        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">{{ __('posts.title_required') }}</label>
            <input type="text" name="title" id="title" required
                   class="appearance-none rounded relative block w-full px-3 py-2 border @error('title') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-sky-700 focus:border-sky-700 sm:text-sm"
                   value="{{ old('title') }}"
                   placeholder="{{ __('posts.enter_title') }}">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">{{ __('posts.excerpt') }}</label>
            <textarea name="excerpt" id="excerpt" rows="2"
                      class="appearance-none rounded relative block w-full px-3 py-2 border @error('excerpt') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-sky-700 focus:border-sky-700 sm:text-sm"
                      placeholder="{{ __('posts.excerpt_placeholder') }}">{{ old('excerpt') }}</textarea>
            @error('excerpt')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">{{ __('posts.content_required') }}</label>
            <textarea name="content" id="content" rows="15" required
                      class="appearance-none rounded relative block w-full px-3 py-2 border @error('content') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-sky-700 focus:border-sky-700 sm:text-sm font-mono"
                      placeholder="{{ __('posts.content_placeholder') }}">{{ old('content') }}</textarea>
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('posts.categories') }}</label>
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded p-3 bg-gray-50">
                    @forelse($categories as $category)
                        <label class="flex items-center">
                            <input type="checkbox" name="categories[]" value="{{ $category['id'] }}"
                                   class="rounded border-gray-300 text-sky-800 focus:ring-sky-700"
                                   {{ in_array($category['id'], old('categories', [])) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">{{ $category['name'] }}</span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500">{{ __('posts.no_categories_available') }}</p>
                    @endforelse
                </div>
            </div>

            <div>
                <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('posts.tags') }}
                </label>
                <input type="text" name="tags" id="tags"
                       class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-sky-700 focus:border-sky-700 sm:text-sm"
                       placeholder="php, laravel, tutorial"
                       value="{{ old('tags') }}">
                <p class="mt-1 text-xs text-gray-500">{{ __('posts.available_tags') }}
                    @foreach($tags as $tag)
                        <span class="cursor-pointer hover:text-sky-800" onclick="addTag('{{ $tag['name'] }}')">{{ $tag['name'] }}</span>@if(!$loop->last), @endif
                    @endforeach
                </p>
            </div>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <a href="{{ route('panel.posts') }}" class="text-sm text-gray-600 hover:text-gray-900">
                &larr; {{ __('general.back_to_list') }}
            </a>
            <div class="flex items-center space-x-4">
                <button type="submit" name="status" value="draft"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-700">
                    {{ __('posts.save_as_draft') }}
                </button>
                <button type="submit" name="status" value="published"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sky-800 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-700">
                    {{ __('posts.publish') }}
                </button>
            </div>
        </div>
    </form>

    <script>
        function addTag(tag) {
            const input = document.getElementById('tags');
            const currentTags = input.value.split(',').map(t => t.trim()).filter(t => t);
            if (!currentTags.includes(tag)) {
                currentTags.push(tag);
                input.value = currentTags.join(', ');
            }
        }
    </script>
@endsection
