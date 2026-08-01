<x-lareon::admin-list-creator :href="route('admin.settings.meta.templates.store')">
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('templates')]))
    @section('description', __('templates are collections of content fields that can be assigned to different templates, allowing you to add template-specific content to each one'))

    @section('header.start')
        <x-lareon::links.nav :href="route('admin.settings.meta.elements.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('elements')])" color="index" can="admin.meta.element.read"/>
    @endsection

    @section('form')
     <div class="space-y-6">
         <x-lareon::editor.input :required="true" type="text" :label="__('title')" name="title" :placeholder="__('lareon::global.placeholders.write.unique.one',['attribute'=>__('title')])"/>
         <x-lareon::editor.input-select :label="__('template')" name="template" >
            @foreach($unregistered as $newOne)
                <option value="{{$newOne}}">{{$newOne}}</option>
            @endforeach
         </x-lareon::editor.input-select>
     </div>
    @endsection
    @section('list')
        <x-lareon::table :rows="$registered" :headers="['id'=>'#','title'=>__('title'),'template'=>__('template'),'created_at'=>__('created at') ,'']">
            @foreach($registered as $key=>$template)
                <tr>
                    <td class="p-3">{{$registered->firstItem() + $key}}</td>
                    <td>{{$template->title}}</td>
                    <td>{{$template->template}}</td>
                    <td> <x-lareon::date :date="$template->created_at"/> </td>
                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="edit" :href="route('admin.settings.meta.templates.edit' , $template)" can="admin.meta.template.edit"/>
                            <x-lareon::links.action type="delete" method="delete"  :href="route('admin.settings.meta.templates.destroy' , $template)" can="admin.meta.template.delete"/>
                        </x-lareon::action-box>
                    </td>
                </tr>
            @endforeach
                <x-slot:foot>
                    <tr>
                        <td  colspan="9" class="p-2">
                            {!! $registered->appends(request()->query())->links() !!}
                        </td>
                    </tr>
                </x-slot:foot>
        </x-lareon::table>
    @endsection

</x-lareon::admin-list-creator>
