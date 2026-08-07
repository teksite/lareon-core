<x-lareon::admin-editor method="update" :instance="$element" :action="route('admin.settings.meta.elements.update', $element)" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.edit_current',['attribute'=>__('element') , 'item'=>($element->title)]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.settings.meta.elements.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('elements')])" color="index" can="admin.meta.element.read"/>
        <x-lareon::links.nav :href="route('admin.settings.meta.elements.create')" :content="__('lareon::global.buttons.new_attribute' ,['attribute'=>__('element')])" color="create" can="admin.meta.element.create"/>
    @endsection
    @section('header.end')
        <x-lareon::links.action type="delete" :href="route('admin.settings.meta.elements.destroy', $element)" method="delete" :label="trans('lareon::global.buttons.delete')" can="admin.meta.element.delete"/>
    @endsection

    @section('form')
        <x-lareon::editor.tabs.item :title="__('basic data')">
            <x-lareon::editor.tabs.section>
                <div class="grid gap-6 lg:grid-cols-2">
                    <x-lareon::editor.input :required="true" :label="__('title')" name="title" :value="$element->title" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('title') , 'item'=>__('element')])"/>
                </div>
            </x-lareon::editor.tabs.section>

            <x-lareon::editor.tabs.item :title="__('arguments')">
                <x-lareon::editor.tabs.section>
                    <x-meta::admin.element-args :value="old('args.settings' , $element->settings)"/>
                </x-lareon::editor.tabs.section>
            </x-lareon::editor.tabs.item>

        </x-lareon::editor.tabs.item>
    @endsection
</x-lareon::admin-editor>
