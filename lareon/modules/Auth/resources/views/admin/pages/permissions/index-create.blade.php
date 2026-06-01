<x-lareon::admin-list-creator>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('permissions')]))
    @section('description', __('Manage all registered permissions, view their details and control account status'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.permissions.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.permission.create"/>
    @endsection
    @section('list')
        <x-lareon::table :rows="$permissions" :headers="['id'=>'#','title'=>__('title'),'created_at'=>__('created at') ,'']">
            @foreach($permissions as $key=>$permission)
                <tr>
                    <td class="p-3">{{$permissions->firstItem() + $key}}</td>

                    <td>{{$permission->fullname}}</td>
                    <td> <x-lareon::date :date="$permission->created_at"/> </td>

                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="edit" :href="route('admin.permissions.edit' , $permission)" can="admin.permission.edit"/>
                            <x-lareon::links.action type="delete" :href="route('admin.permissions.destroy' , $permission)" can="admin.permission.delete"/>
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
