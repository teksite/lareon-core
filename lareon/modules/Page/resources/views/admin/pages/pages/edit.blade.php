<x-lareon::admin-editor :action="route('admin.pages.update' , $page)" :instance="$page">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('page')]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.pages.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('pages')])" color="index"/>
    @endsection
    @section('form')

        <x-lareon::editor.tabs.item :title="__('basic data')">
            <x-lareon::editor.input :required="true" labelPosition="start" :label="__('title')" name="title" :value="old('title', $page->title)" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('page')])"/>
            <x-lareon::editor.input-slug :required="true" labelPosition="start" :label="__('slug')" :value="old('slug', $page->slug)" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('slug') , 'item'=>__('page')])"/>

            <legend class="legend">{{__('content')}}</legend>
            <fieldset class="fieldset space-y-6">

                <div class="space-y-6">
                    <x-lareon::editor.input-textarea :required="false" :label="__('excerpt')" name="excerpt" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('excerpt')])">{!! old('excerpt', $page->excerpt) !!}</x-lareon::editor.input-textarea>
                    <x-lareon::editor.input-editor rows="9" :required="false" :label="__('body')" name="body" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('body')])">{!! old('body', $page->body) !!}</x-lareon::editor.input-editor>
                </div>
            </fieldset>
            <x-slot:aside>
                <x-lareon::editor.input-image :required="false" wrapperMode="y-box" :value="old('image', $page->getImage->url)" name="image"/>
                <x-lareon::editor.section.template :required="false" wrapperMode="y-box"/>
                <x-lareon::editor.section.status-publish :required="false" wrapperMode="y-box"/>
            </x-slot:aside>
        </x-lareon::editor.tabs.item>

    @endsection
</x-lareon::admin-editor>
