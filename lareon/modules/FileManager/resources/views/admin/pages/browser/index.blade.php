<x-lareon::admin-list>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('roles')]))
    @section('description', __('roles control user access via ACL'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.authorize.roles.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.role.create"/>
    @endsection
    @section('list')
        <x-lareon::table :rows="$roles" :headers="['id'=>'#','title'=>__('title') ,'hierarchy'=>__('hierarchy')  ,'created_at'=>__('created at') ,'']">
            @foreach($roles as $key=>$role)
                <tr>
                    <td class="p-3">{{$roles->firstItem() + $key}}</td>
                    <td>{{$role->title}}</td>
                    <td>{{$role->hierarchy}}</td>
                    <td>
                        <x-lareon::date :date="$role->created_at"/>
                    </td>
                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="edit" :href="route('admin.authorize.roles.edit' , $role)" can="admin.role.edit"/>
                            <x-lareon::links.action type="delete" method="delete" :href="route('admin.authorize.roles.destroy' , $role)" can="admin.role.delete"/>
                        </x-lareon::action-box>
                    </td>
                </tr>
            @endforeach
            <x-slot:foot>
                <tr>
                    <td colspan="9" class="p-2">
                        {!! $roles->appends(request()->query())->links() !!}
                    </td>
                </tr>
            </x-slot:foot>
        </x-lareon::table>

    @endsection

</x-lareon::admin-list>
