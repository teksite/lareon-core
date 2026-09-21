<x-lareon::admin-editor method="update" :instance="$admin" :action="route('admin.admins.update', $admin)" :publishInfo="true" :hasTab="false"  :publishStatus="false">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('admin')]) . "($admin->name)")
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.admins.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('admins')])" color="index" can="admin.admin.read"/>
        <x-lareon::links.nav :href="route('admin.admins.create')" :content="__('lareon::global.buttons.new_attribute' ,['attribute'=>__('admin')])" color="create" can="admin.admin.create"/>
    @endsection

    @section('header.end')
        <x-lareon::links.action type="delete" :href="route('admin.admins.destroy', $admin)" method="delete" :label="trans('lareon::global.buttons.delete')" can="admin.admin.delete"/>
    @endsection

    @section('form')

        <x-lareon::editor.tabs.item :title="__('basic data')">
            <x-lareon::editor.tabs.section>
                <div class="grid gap-6 lg:grid-cols-2">
                    <x-lareon::editor.input :required="true" :label="__('first name')" name="name" :value="$admin->name" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('name') , 'item'=>__('admin')])"/>
                    <x-lareon::editor.input :required="true" type="email" dir="ltr" :value=" $admin->email" :label="__('email')" name="email" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('email') , 'item'=>__('admin') ])"/>
                </div>
                <x-lareon::editor.input-radio :value="$admin->active" type="inline" :required="true" :options="[[__('no') , 0] , [__('yes') ,1]]" :label="__('active')" name="active" inputsClass="flex items-center gap-1"/>

            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.item>

        <x-lareon::editor.tabs.item :title="__('password')">
            <x-lareon::editor.tabs.section>
                <x-lareon::editor.input-password :label="__('password')" :confirm_label="__('confirm password')" name="password" :placeholder="__('lareon::global.placeholders.auth.password',['attribute'=>__('password')])" wrapperClass="grid gap-6 lg:grid-cols-2"/>
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.item>



    @endsection
    @section('aside')
        <x-user::user-card :user="$admin" :link="false"/>
    @endsection
</x-lareon::admin-editor>
