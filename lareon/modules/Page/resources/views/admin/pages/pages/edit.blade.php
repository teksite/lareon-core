<x-lareon::admin-editor :action="route('admin.pages.update' , $page)"  type="update" :instance="$page" :publishStatus="true" :publishStatus="true">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('page') . " ($page->title)"]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.pages.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('pages')])" color="index"/>
        <x-lareon::links.nav :href="route('admin.pages.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.page.create"/>
    @endsection

    @section('form')

        <x-lareon::editor.tabs.item :title="__('content')">

            <div class="space-y-6">
                <x-lareon::editor.input :required="true" labelPosition="start" :label="__('title')" name="title" :value="old('title', $page->title)" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('page')])"/>
                <x-lareon::editor.input-slug :required="true" labelPosition="start" :label="__('slug')" :value="old('slug', $page->slug)" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('slug') , 'item'=>__('page')])"/>
            </div>

            <div class="space-y-6 y-box">
                <x-lareon::editor.input-textarea :required="false" :label="__('excerpt')" name="excerpt" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('excerpt')])">{!! old('excerpt', $page->excerpt) !!}</x-lareon::editor.input-textarea>
                <x-lareon::editor.input-editor rows="9" :required="false" :label="__('body')" name="body" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('body')])">{!! old('body', $page->body) !!}</x-lareon::editor.input-editor>
            </div>

            <x-slot:aside>
                <x-lareon::editor.input-image :required="false" wrapperMode="y-box" :value="old('primary_media_id' , $page->primaryMedia?->id)" name="primary_media_id"/>
                <x-lareon::editor.section.template :required="false" wrapperMode="y-box"/>
            </x-slot:aside>

        </x-lareon::editor.tabs.item>

    @endsection

</x-lareon::admin-editor>
