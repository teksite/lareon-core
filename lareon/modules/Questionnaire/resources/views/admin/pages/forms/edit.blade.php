<x-lareon::admin-editor :action="route('admin.questionnaire.forms.update' , $form)" method="update" :instance="$form">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('form') . " ($form->title)"]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.questionnaire.forms.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('forms')])" color="index" can="admin.questionnaire.form.read"/>
        <x-lareon::links.nav :href="route('admin.questionnaire.forms.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.questionnaire.form.create"/>
    @endsection
    @section('header.end')
        <x-lareon::links.action type="delete" :href="route('admin.questionnaire.forms.destroy', $form)" method="delete" :label="trans('lareon::global.buttons.delete')" can="admin.form.delete"/>
    @endsection

    @section('form')
        <x-lareon::editor.tabs.item :title="__('body')">
            <x-lareon::editor.tabs.section>
                <x-lareon::editor.input :required="true" labelPosition="start" :label="__('title')" name="title" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('form')])" :value="$form->title"/>
                <x-lareon::editor.input-textarea :required="false" :label="__('body')" name="body">{{$form->body}}</x-lareon::editor.input-textarea>
            </x-lareon::editor.tabs.section>

            <x-slot:aside>
                <x-lareon::editor.tabs.section>
                    <x-lareon::editor.input-check :options="[[__('active') , 1 ]]" name="active" :value="$form->active"/>
                    <x-lareon::editor.input-check :options="[[__('has file') , 1 ]]" name="has_file" :value="$form->has_file"/>
                    <x-lareon::editor.input-check :options="[[__('response to client') , 1 ]]" name="response_client" :value="$form->response_client"/>
                </x-lareon::editor.tabs.section>
                <x-lareon::editor.tabs.section>
                    <x-questionnaire::editor.template  :value="$form->template"/>

                </x-lareon::editor.tabs.section>
            </x-slot:aside>
        </x-lareon::editor.tabs.item>

        <x-lareon::editor.tabs.item :title="__('rules')">
            <x-lareon::editor.tabs.section>
                <x-questionnaire::editor.rules :value="$form->validationRules?->rules ?? []"/>
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.item>

        <x-lareon::editor.tabs.item :title="__('announcements')">
            <x-lareon::editor.tabs.section>
                <x-questionnaire::editor.announcements :value="$form->announcement?->toArray() ?? []"/>
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.item>
    @endsection

</x-lareon::admin-editor>
