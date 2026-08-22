<x-lareon::admin-editor method="create" :action="route('admin.notifications.store')">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('notification')]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.notifications.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('notifications')])" color="index"/>
    @endsection
    @section('form')
        <x-lareon::editor.tabs.item :title="__('content')">
            <x-lareon::editor.tabs.section>
                <x-lareon::editor.input :required="true" labelPosition="top" :label="__('title')" name="title" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('notification')])"/>
                <x-lareon::editor.input-textarea :required="false" :label="__('message')" name="message" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('message')])"></x-lareon::editor.input-textarea>
            </x-lareon::editor.tabs.section>
            <x-slot:aside>


            </x-slot:aside>
        </x-lareon::editor.tabs.item>
    @endsection
</x-lareon::admin-editor>
