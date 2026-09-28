<x-lareon::admin-editor :action="route('admin.visual.gadgets.update' , $gadget)" method="update" :instance="$gadget" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('gadget') . " ($gadget->title)"]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.visual.gadgets.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('gadgets')])" color="index"/>
        <x-lareon::links.nav :href="route('admin.visual.gadgets.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.gadget.create"/>
    @endsection
    @section('header.end')
        <x-lareon::links.action type="delete" :href="route('admin.visual.gadgets.destroy', $gadget)" method="delete" :label="trans('lareon::global.buttons.delete')" can="admin.gadget.delete"/>

    @endsection

    @section('form')
        <x-lareon::editor.tabs.section>
            <x-lareon::editor.input :required="true" :label="__('title')" name="title" :value="$gadget->title" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('gadget')])"/>
            <x-lareon::editor.section.template :required="false" path="gadgets" :value="old('template' , $gadget->template ?? null)"/>
            <x-lareon::editor.input-textarea :required="false" :label="__('body')" name="body">{!! $gadget->body !!}</x-lareon::editor.input-textarea>
        </x-lareon::editor.tabs.section>
    @endsection

</x-lareon::admin-editor>
