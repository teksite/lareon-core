<x-lareon::admin-editor :action="route('admin.users.store')" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('user')]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.users.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('users')])" color="index"/>
    @endsection
    @section('form')
        <x-lareon::box type="y">
            <fieldset class="fieldset space-y-6">
                <legend class="legend">{{__('basic data')}}</legend>
                <div class="grid gap-6 lg:grid-cols-2">
                    <x-lareon::editor.input :required="true" :label="__('title')" name="title" :value="old('title')" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('role')])"/>
                    <x-lareon::editor.input :required="false" :label="__('description')" name="description" :value="old('description')" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('description')])"/>
                </div>
            </fieldset>
        </x-lareon::box>
        <x-lareon::box type="y">
            <fieldset class="fieldset space-y-6">
                <legend class="legend">{{__('permissions')}}</legend>
                <x-auth::editor.permissions-tree :permissions="$permissions" />
            </fieldset>
        </x-lareon::box>
    @endsection
    @section('aside')

    @endsection
</x-lareon::admin-editor>
