<x-lareon::admin-editor method="update" :action="route('admin.admins.acl.update', $admin)" :hasTab="false">
    @section('title', __('admin accessibility') . "($admin->name)")
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.admins.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('admins')])" color="index" can="admin.admin.read"/>
    @endsection

    @section('form')

        <x-lareon::editor.tabs.item :title="__('roles')">
            <x-lareon::editor.tabs.section>
                <x-auth::editor.roles-tree :value="$admin->roles?->pluck('id')->toArray() ?? []" :rolesGroup="$rolesGroup"/>
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.item>

        <x-lareon::editor.tabs.item :title="__('permissions')">
            <x-lareon::editor.tabs.section>
                <x-auth::editor.permissions-tree :permissions="$permissions" :value="$admin->permissions?->pluck('id')->toArray() ?? []"/>
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.item>
    @endsection
</x-lareon::admin-editor>
