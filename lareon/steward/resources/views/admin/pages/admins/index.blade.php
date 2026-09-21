<x-lareon::admin-list>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('admins')]))
    @section('description', __('Manage all registered admins, view their details and control account status'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.admins.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.admin.create"/>
    @endsection
    @section('list')
        <x-lareon::table :rows="$admins" :headers="['id'=>'#' ,'name'=>__('name'),'email'=>__('email'),'created_at'=>__('created at') ,'parent_id'=> __('creator') ,'']">
            @foreach($admins as $key=>$admin)
                <tr class="{{$admin->active === 1 ?: 'bg-red-100'}}">
                    <td class="p-3">{{$admins->firstItem() + $key}}</td>

                    <td>{{$admin->name}}</td>
                    <td>{{$admin->email}}</td>
                    <td>
                        <x-lareon::date :date="$admin->created_at"/>
                    </td>
                    <td> {{$admin->parent()?->name ?? '-'}} </td>
                    <td>
                        <x-lareon::action-box class="action">
                            @if(\Illuminate\Support\Facades\Route::has('admin.admins.acl.edit'))
                                <x-lareon::links.action type="setting" :href="route('admin.admins.acl.edit' , $admin)" can="admin.admin.acl.edit"/>
                            @endif
                            <x-lareon::links.action type="edit" :href="route('admin.admins.edit' , $admin)" can="admin.admin.edit"/>
                            <x-lareon::links.action type="delete" method="delete" :href="route('admin.admins.destroy' , $admin)" can="admin.admin.delete"/>
                        </x-lareon::action-box>
                    </td>
                </tr>
            @endforeach
            <x-slot:foot>
                <tr>
                    <td colspan="9" class="p-2">
                        {!! $admins->appends(request()->query())->links() !!}
                    </td>
                </tr>
            </x-slot:foot>
        </x-lareon::table>

    @endsection

</x-lareon::admin-list>
