@extends('layouts.panel')

@section('title', __('panel.edit_post_panel'))

@section('panel-title', __('posts.edit_post'))

@section('panel-content')
    <form method="POST" action="{{ route('panel.posts.update', $post['id']) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('posts.title_required') }}</label>
            <input type="text" name="title" id="title" required
                   class="appearance-none rounded relative block w-full px-3 py-2 border @error('title') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-sky-700 dark:focus:ring-sky-500 focus:border-sky-700 dark:focus:border-sky-500 sm:text-sm"
                   value="{{ old('title', $post['title'] ?? '') }}"
                   placeholder="{{ __('posts.enter_title') }}">
            @error('title')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('posts.slug') }}</label>
            <input type="text" name="slug" id="slug"
                   class="appearance-none rounded relative block w-full px-3 py-2 border @error('slug') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-sky-700 dark:focus:ring-sky-500 focus:border-sky-700 dark:focus:border-sky-500 sm:text-sm font-mono"
                   value="{{ old('slug', $post['slug'] ?? '') }}"
                   placeholder="my-post-url">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('posts.slug_hint') }}</p>
            @error('slug')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('posts.excerpt') }}</label>
            <textarea name="excerpt" id="excerpt" rows="2"
                      class="appearance-none rounded relative block w-full px-3 py-2 border @error('excerpt') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-sky-700 dark:focus:ring-sky-500 focus:border-sky-700 dark:focus:border-sky-500 sm:text-sm"
                      placeholder="{{ __('posts.excerpt_placeholder') }}">{{ old('excerpt', $post['excerpt'] ?? '') }}</textarea>
            @error('excerpt')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('posts.content_required') }}</label>
            <textarea name="content" id="content" required>{{ old('content', $post['content'] ?? '') }}</textarea>
            @error('content')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('posts.categories') }}</label>
                @php
                    $postCategories = collect($post['categories'] ?? [])->pluck('id')->toArray();
                @endphp
                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded p-3 bg-gray-50 dark:bg-gray-700/50">
                    @forelse($categories as $category)
                        <label class="flex items-center">
                            <input type="checkbox" name="categories[]" value="{{ $category['id'] }}"
                                   class="rounded border-gray-300 dark:border-gray-600 text-sky-800 dark:text-sky-500 focus:ring-sky-700 dark:bg-gray-700"
                                   {{ in_array($category['id'], old('categories', $postCategories)) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $category['name'] }}</span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('posts.no_categories_available') }}</p>
                    @endforelse
                </div>
            </div>

            <div>
                @php
                    $postTags = collect($post['tags'] ?? [])->pluck('name')->implode(', ');
                @endphp
                <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('posts.tags') }}
                </label>
                <input type="text" name="tags" id="tags"
                       class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-sky-700 dark:focus:ring-sky-500 focus:border-sky-700 dark:focus:border-sky-500 sm:text-sm"
                       placeholder="php, laravel, tutorial"
                       value="{{ old('tags', $postTags) }}">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('posts.available_tags') }}
                    @foreach($tags as $tag)
                        <span class="cursor-pointer hover:text-sky-800 dark:hover:text-sky-400" onclick="addTag('{{ $tag['name'] }}')">{{ $tag['name'] }}</span>@if(!$loop->last), @endif
                    @endforeach
                </p>
            </div>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('panel.posts') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                &larr; {{ __('general.back_to_list') }}
            </a>
            <div class="flex items-center space-x-4">
                <button type="submit" name="status" value="draft"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-sky-700">
                    {{ __('posts.save_as_draft') }}
                </button>
                <button type="submit" name="status" value="published"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sky-800 hover:bg-sky-700 dark:bg-sky-700 dark:hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-sky-700">
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

        document.addEventListener('DOMContentLoaded', function () {
            const textarea = document.getElementById('content');
            const editor = new EasyMDE({
                element: textarea,
                spellChecker: false,
                toolbar: ['bold','italic','heading-1','heading-2','|','quote','code','|','unordered-list','ordered-list','|','link','|','preview','guide'],
                minHeight: '320px',
            });
            editor.codemirror.on('change', () => {
                textarea.value = editor.value();
            });
        });
    </script>
@endsection
