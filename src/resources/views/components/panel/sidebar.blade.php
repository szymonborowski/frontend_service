<nav class="bg-white rounded-lg shadow p-4">
    <x-panel.menu-category :title="__('panel.user_section')">
        <x-panel.menu-item
            :href="route('panel.profile')"
            :active="request()->routeIs('panel.profile*')"
            icon="user"
        >
            {{ __('panel.user_data') }}
        </x-panel.menu-item>
    </x-panel.menu-category>

    <x-panel.menu-category :title="__('panel.blog_section')" class="mt-6">
        <x-panel.menu-item
            :href="route('panel.posts')"
            :active="request()->routeIs('panel.posts') || request()->routeIs('panel.posts.edit')"
            icon="posts"
        >
            {{ __('panel.my_posts') }}
        </x-panel.menu-item>
        <x-panel.menu-item
            :href="route('panel.comments')"
            :active="request()->routeIs('panel.comments')"
            icon="comments"
        >
            {{ __('panel.my_comments') }}
        </x-panel.menu-item>
        <x-panel.menu-item
            :href="route('panel.posts.create')"
            :active="request()->routeIs('panel.posts.create')"
            icon="plus"
        >
            {{ __('panel.new_post') }}
        </x-panel.menu-item>
    </x-panel.menu-category>
</nav>
