<x-lareon::admin-editor method="create" :action="route('admin.admins.store')" :hasTab="false" :insatence="$admin">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('admin')]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.admins.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('admins')])" color="index"/>
    @endsection
    @section('form')
        <x-lareon::editor.tabs.item :title="__('basic data')">
            <x-lareon::editor.tabs.section>
                <div class="grid gap-6 lg:grid-cols-2">
                    <x-lareon::editor.input :required="true" :label="__('first name')" name="name" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('name') , 'item'=>__('admin')])"/>
                    <x-lareon::editor.input :required="true" type="email" dir="ltr" :label="__('email')" name="email" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('email') , 'item'=>__('admin') ])"/>
                </div>

                <div class="">
                    <x-lareon::editor.input-password :label="__('password')" :confirm_label="__('confirm password')" name="password" :placeholder="__('lareon::global.placeholders.auth.password',['attribute'=>__('password')])" :required="true" wrapperClass="grid gap-6 lg:grid-cols-2"/>
                </div>

                <x-lareon::editor.input-radio type="inline" :required="true" value="0" :options="[[__('no') , 0] , [__('yes') ,1]]" :label="__('active')" name="active" inputsClass="flex items-center gap-1"/>

            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.item>

    @endsection
</x-lareon::admin-editor>
