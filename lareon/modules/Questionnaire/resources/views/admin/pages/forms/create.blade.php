<x-lareon::admin-editor method="create" :action="route('admin.questionnaire.forms.store')" :instance="$form">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('form')]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.questionnaire.forms.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('forms')])" color="index"/>
    @endsection
    @section('form')
        <x-lareon::editor.tabs.item :title="__('body')">
            <x-lareon::editor.tabs.section>
                <x-lareon::editor.input :required="true" labelPosition="start" :label="__('title')" name="title" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('form')])"/>
                <x-lareon::editor.input-textarea :required="false" :label="__('body')" name="body"></x-lareon::editor.input-textarea>
            </x-lareon::editor.tabs.section>

            <x-slot:aside>
                <x-lareon::editor.tabs.section>
                    <x-lareon::editor.input-check :options="[[__('active') , 1 ]]" name="active" value="1"/>
                    <x-lareon::editor.input-check :options="[[__('has file') , 1 ]]" name="has_file" value="0"/>
                    <x-lareon::editor.input-check :options="[[__('response to client') , 1 ]]" name="response_client" value="0"/>
                </x-lareon::editor.tabs.section>
            </x-slot:aside>
        </x-lareon::editor.tabs.item>
        <x-lareon::editor.tabs.item :title="__('rules')">
            <x-lareon::editor.tabs.section>
                <x-questionnaire::editor.rules/>
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.item>
        <x-lareon::editor.tabs.item :title="__('announcements')">
            <x-lareon::editor.tabs.section>
                <x-questionnaire::editor.announcements />
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.item>

    @endsection

</x-lareon::admin-editor>
