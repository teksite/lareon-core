<x-lareon::admin-editor type="update" method="patch" :instance="$template" :action="route('admin.settings.meta.templates.update', $template)" :publishInfo="false" :publishStatus="false">
    @section('title', __('lareon::global.crud.titles.edit_current',['attribute'=>__('template') , 'item'=>($template->title)]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.settings.meta.templates.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('templates')])" color="index" can="admin.meta.template.read"/>
    @endsection
    @section('header.end')
        <x-lareon::links.action type="delete" :href="route('admin.settings.meta.templates.destroy', $template)" method="delete" :label="trans('lareon::global.buttons.delete')" can="admin.meta.template.delete"/>
    @endsection

    @section('form')
        <x-lareon::editor.tabs.item :title="__('basic data')">
            <x-lareon::editor.tabs.section>
                <div class="grid gap-6 lg:grid-cols-2">
                    <x-lareon::editor.input :required="true" :label="__('title')" name="title" :value="$template->title" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('title') , 'item'=>__('template')])"/>
                </div>
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.item>
    @endsection
</x-lareon::admin-editor>
