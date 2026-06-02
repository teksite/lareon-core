<x-lareon::admin-editor type="update" method="patch" :instance="$user" :action="route('admin.users.acl.update', $user)">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('user')]) . "($user->title)")
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.users.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('users')])" color="index" can="admin.user.read"/>
    @endsection

    @section('form')
        <x-lareon::editor.tabs.item :title="__('roles')">
            <div class="grid gap-6 lg:grid-cols-2">
                <x-lareon::editor.input :required="true" :label="__('title')" name="title" :value="$user->title" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('role')])"/>
                <x-lareon::editor.input :required="false" :label="__('description')" name="description" :value="$user->description" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('description')])"/>
                <x-lareon::editor.input :required="true" min="0" max="100" step="1" :label="__('hierarchy')" name="hierarchy" :value="$user->hierarchy" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('description')])"/>
            </div>
        </x-lareon::editor.tabs.item>>
        <x-lareon::editor.tabs.item :title="__('roles')">

            <x-auth::editor.permissions-tree :permissions="$permissions" :value="$user->permissions?->pluck('id')->toArray() ?? []"/>
        </x-lareon::editor.tabs.item>
    @endsection
</x-lareon::admin-editor>
