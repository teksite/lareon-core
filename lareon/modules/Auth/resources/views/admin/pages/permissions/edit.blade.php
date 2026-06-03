<x-lareon::admin-editor type="update" method="patch" :instance="$permission" :action="route('admin.authorize.permissions.update', $permission)">
    @section('title', __('lareon::global.crud.titles.edit_current',['attribute'=>__('permission') , 'item'=>($permission->title)]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.authorize.permissions.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('permissions')])" color="index" can="admin.permission.read"/>
    @endsection
    @section('header.end')
        <x-lareon::links.action type="delete" :href="route('admin.authorize.permissions.destroy', $permission)" method="delete" :label="trans('lareon::global.buttons.delete')" can="admin.permission.delete"/>
    @endsection

    @section('form')
        <x-lareon::editor.tabs.item :title="__('basic data')">
            <div class="grid gap-6 lg:grid-cols-2">
                <x-lareon::editor.input :required="true" :label="__('title')" name="title" :value="$permission->title" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('title') , 'item'=>__('permission')])"/>
                <x-lareon::editor.input :required="false" :label="__('description')" name="description" :value="$permission->description" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('description')])"/>

            </div>
            <div class="danger-msg flex items-center gap-2 justify-start">
                <div class="relative inline-flex size-3">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-600 opacity-75"></span>
                    <span class="relative inline-flex size-3 rounded-full bg-red-600"></span>
                </div>
                <p>
                    {{__('changing the title of a permission may affect the accessibility of roles and users to different parts of the app')}}.
                </p>
            </div>
        </x-lareon::editor.tabs.item>
    @endsection
</x-lareon::admin-editor>
