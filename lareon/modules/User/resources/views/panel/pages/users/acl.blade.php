<x-lareon::admin-editor type="update" method="patch" :action="route('admin.users.acl.update', $user)">
    @section('title', __('user accessibility') . "($user->name)")
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.users.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('users')])" color="index" can="admin.user.read"/>
    @endsection

    @section('form')
        <x-lareon::editor.tabs.item :title="__('roles')">
                <x-auth::editor.roles-tree :value="$user->roles?->pluck('id')->toArray() ?? []" :rolesGroup="$rolesGroup"/>
        </x-lareon::editor.tabs.item>
        <x-lareon::editor.tabs.item :title="__('permissions')">
            <x-auth::editor.permissions-tree :permissions="$permissions" :value="$user->permissions?->pluck('id')->toArray() ?? []"/>
        </x-lareon::editor.tabs.item>
    @endsection
</x-lareon::admin-editor>
