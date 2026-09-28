<x-lareon::admin-editor method="create" :action="route('admin.visual.gadgets.store')" :instance="$gadget" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('gadget')]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.visual.gadgets.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('gadgets')])" color="index"/>
    @endsection
    @section('form')
        <x-lareon::editor.tabs.section>
            <x-lareon::editor.input :required="true"  :label="__('title')" name="title" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('gadget')])"/>
        <x-lareon::editor.section.template :value="old('template')" path="gadgets"/>
            <x-lareon::editor.input-textarea :required="false" :label="__('body')" name="body"></x-lareon::editor.input-textarea>
        </x-lareon::editor.tabs.section>
    @endsection

</x-lareon::admin-editor>
