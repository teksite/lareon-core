<x-lareon::admin-list-creator :href="route('admin.settings.meta.elements.store')">
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('elements')]))
    @section('description', __('each permission determines access to a specific section or feature in the application'))
    @section('form')
     <div class="space-y-6">
         <x-lareon::editor.input :required="true" type="text" :label="__('title')" name="title" :placeholder="__('lareon::global.placeholders.write.unique.one',['attribute'=>__('title')])"/>
         <x-lareon::editor.input-select :label="__('element')" name="element" >
            @foreach($unregistered as $newOne)
                <option value="{{$newOne}}">{{$newOne}}</option>
            @endforeach
         </x-lareon::editor.input-select>
     </div>
    @endsection
    @section('list')
        <x-lareon::table :rows="$registered" :headers="['id'=>'#','title'=>__('title'),'created_at'=>__('created at') ,'']">
            @foreach($registered as $key=>$element)
                <tr>
                    <td class="p-3">{{$registered->firstItem() + $key}}</td>
                    <td>{{$element->title}}</td>
                    <td> <x-lareon::date :date="$element->created_at"/> </td>
                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="edit" :href="route('admin.settings.meta.elements.edit' , $element)" can="admin.meta.element.edit"/>
                            <x-lareon::links.action type="delete" method="delete"  :href="route('admin.settings.meta.elements.destroy' , $element)" can="admin.meta.element.delete"/>
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
