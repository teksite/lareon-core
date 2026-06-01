<x-lareon::admin-list-creator :href="route('admin.authorize.permissions.store')">
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('permissions')]))
    @section('description', __('each permission determines access to a specific section or feature in the application'))
    @section('form')
     <div class="space-y-6">
         <x-lareon::editor.input :required="true" type="text" :label="__('title')" name="title" :placeholder="__('lareon::global.placeholders.write.unique.one',['attribute'=>__('title')])"/>
         <x-lareon::editor.input type="text" :label="__('description')" name="description" :placeholder="__('lareon::global.placeholders.write.unique.one',['attribute'=>__('description')])"/>
     </div>
    @endsection
    @section('list')
        <x-lareon::table :rows="$permissions" :headers="['id'=>'#','title'=>__('title'),'created_at'=>__('created at') ,'']">
            @foreach($permissions as $key=>$permission)
                <tr>
                    <td class="p-3">{{$permissions->firstItem() + $key}}</td>
                    <td>{{$permission->title}}</td>
                    <td> <x-lareon::date :date="$permission->created_at"/> </td>
                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="edit" :href="route('admin.authorize.permissions.edit' , $permission)" can="admin.permission.edit"/>
                            <x-lareon::links.action type="delete" :href="route('admin.authorize.permissions.destroy' , $permission)" can="admin.permission.delete"/>
                        </x-lareon::action-box>
                    </td>
                </tr>
            @endforeach
                <x-slot:foot>
                    <tr>
                        <td  colspan="9" class="p-2">
                            {!! $permissions->appends(request()->query())->links() !!}
                        </td>
                    </tr>
                </x-slot:foot>
        </x-lareon::table>
    @endsection

</x-lareon::admin-list-creator>
