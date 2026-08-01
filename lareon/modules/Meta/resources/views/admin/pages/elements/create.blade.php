<x-lareon::admin-editor :action="route('admin.settings.meta.elements.store')" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('element')]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.settings.meta.elements.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('elements')])" color="index"/>
    @endsection
    @section('form')
        <x-lareon::editor.tabs.section>
            <x-lareon::editor.input :required="true" type="text" :label="__('title')" name="title" :placeholder="__('lareon::global.placeholders.write.unique.one',['attribute'=>__('title')])"/>
            <x-lareon::editor.input-select :required="true" :label="__('element')" name="element">
                @foreach($unregistered as $newOne)
                    <option value="{{$newOne}}">{{$newOne}}</option>
                @endforeach
            </x-lareon::editor.input-select>
        </x-lareon::editor.tabs.section>
        <x-lareon::editor.tabs.section>
            <x-meta::admin.element-args :value="old('args')" />
        </x-lareon::editor.tabs.section>

    @endsection
</x-lareon::admin-editor>
