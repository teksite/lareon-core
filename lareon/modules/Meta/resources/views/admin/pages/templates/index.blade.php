<x-lareon::admin-list-creator :href="route('admin.settings.meta.templates.store')">
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('templates')]))
    @section('description', __('templates are custom views that can contain additional fields and a different layout. They can be assigned to pages, allowing them to store extra data and display content with a layout different from the default page template'))

    @section('header.start')
        <x-lareon::links.nav :href="route('admin.settings.meta.elements.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('elements')])" color="index" can="admin.meta.element.read"/>
    @endsection

    @section('form')
        <div class="space-y-6">
            <x-lareon::editor.input :required="true" type="text" :label="__('title')" name="title" :placeholder="__('lareon::global.placeholders.write.unique.one',['attribute'=>__('title')])"/>
            <x-lareon::editor.input-select :label="__('template')" name="template">
                @foreach($unregistered as $key=>$templates)
                    <optgroup label="{{$key}}">
                        @foreach($templates as $template)
                            <option value="{{$key}}|{{$template}}">{{$template}}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </x-lareon::editor.input-select>
        </div>
    @endsection
    @section('list')
        <x-lareon::table :rows="$registered" :headers="['id'=>'#','title'=>__('title'),'template'=>__('template'),'type'=>__('type'),'created_at'=>__('created at') ,'']">
            @foreach($registered as $key=>$template)
                <tr>
                    <td class="p-3">{{$registered->firstItem() + $key}}</td>
                    <td>{{$template->title}}</td>
                    <td>{{$template->template}}</td>
                    <td>{{$template->model_type}}</td>
                    <td>
                        <x-lareon::date :date="$template->created_at"/>
                    </td>
                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="edit" :href="route('admin.settings.meta.templates.edit' , $template)" can="admin.meta.template.edit"/>
                            <x-lareon::links.action type="delete" method="delete" :href="route('admin.settings.meta.templates.destroy' , $template)" can="admin.meta.template.delete"/>
                        </x-lareon::action-box>
                    </td>
                </tr>
            @endforeach
            <x-slot:foot>
                <tr>
                    <td colspan="9" class="p-2">
                        {!! $registered->appends(request()->query())->links() !!}
                    </td>
                </tr>
            </x-slot:foot>
        </x-lareon::table>
    @endsection

</x-lareon::admin-list-creator>
