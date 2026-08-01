<x-lareon::admin-list>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('elements')]))
    @section('description', __('elements are collections of content fields that can be assigned to different templates, allowing you to add template-specific content to each one'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.settings.meta.elements.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.meta.element.create"/>
    @endsection
    @section('list')
        <x-lareon::table :rows="$registered" :headers="['id'=>'#','title'=>__('title'),'element'=>__('element'),'created_at'=>__('created at') ,'']">
            @foreach($registered as $key=>$element)
                <tr>
                    <td class="p-3">{{$registered->firstItem() + $key}}</td>
                    <td>{{$element->title}}</td>
                    <td>{{$element->element}}</td>
                    <td>
                        <x-lareon::date :date="$element->created_at"/>
                    </td>
                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="edit" :href="route('admin.settings.meta.elements.edit' , $element)" can="admin.meta.element.edit"/>
                            <x-lareon::links.action type="delete" method="delete" :href="route('admin.settings.meta.elements.destroy' , $element)" can="admin.meta.element.delete"/>
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

</x-lareon::admin-list>
