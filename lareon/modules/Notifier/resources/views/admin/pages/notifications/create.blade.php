<x-lareon::admin-editor method="create" :action="route('admin.notifications.store')" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('notification')]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.notifications.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('notifications')])" color="index"/>
    @endsection
    @section('form')

        <x-lareon::editor.tabs.section>
            <x-lareon::editor.input :required="true" labelPosition="top" :label="__('title')" name="title" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('notification')])"/>
            <x-lareon::editor.input-textarea :required="false" :label="__('message')" name="message" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('message')])"></x-lareon::editor.input-textarea>
        </x-lareon::editor.tabs.section>

    @endsection

    @section('aside')
        <x-lareon::editor.tabs.section>
            <x-auth::editor.roles-selection :inline="true" :multiple="true"/>
        </x-lareon::editor.tabs.section>

        <x-lareon::editor.tabs.section>
            <x-lareon::editor.input-select :multiple="true" labelPosition="top" :label="__('via')" name="channels[]" :required="true" :inline="true" :value="old('channels')">
                @foreach(\Lareon\Modules\Notifier\App\Enums\ChannelsEnum::cases() as $case)
                    <option value="{{ $case->value }}">{{__($case->name)}}</option>
                @endforeach
            </x-lareon::editor.input-select>
        </x-lareon::editor.tabs.section>
    @endsection
</x-lareon::admin-editor>
