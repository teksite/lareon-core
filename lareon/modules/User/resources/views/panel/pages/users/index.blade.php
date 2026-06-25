<x-lareon::admin-list>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('users')]))
    @section('description', __('Manage all registered users, view their details and control account status'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.users.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.user.create"/>
    @endsection
    @section('list')
        <x-lareon::table :rows="$users" :headers="['id'=>'#',__('featured image') ,'name'=>__('name'),'phone'=>__('phone'),'email'=>__('email'),'created_at'=>__('created at') ,'parent_id'=> __('creator') ,'']">
            @foreach($users as $key=>$user)
                <tr>
                    <td class="p-3">{{$users->firstItem() + $key}}</td>
                    <td>
                        <img src="{{$user->avatar ?? asset('assets/images/avatar-default.jpg')}}" alt="{{$user->name}}" width="35" height="35" fetchpriority="low" decoding="async" loading="lazy">
                    </td>
                    <td>{{$user->fullname}}</td>
                    <td>{{$user->phone}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <x-lareon::date :date="$user->created_at"/>
                    </td>
                    <td> {{$user->parent()?->fullname ?? '-'}} </td>
                    <td>
                        <x-lareon::action-box class="action">
                            @if(\Illuminate\Support\Facades\Route::has('admin.users.meta.edit'))
                                <x-lareon::links.action type="sub" :href="route('admin.users.meta.edit' , $user)"/>
                            @endif
                            @if($user->path())
                                <x-lareon::links.action type="show" :href="route('users.show' , $user)"/>
                            @endif
                            @if(\Illuminate\Support\Facades\Route::has('admin.users.acl.edit'))
                                    <x-lareon::links.action type="setting" :href="route('admin.users.acl.edit' , $user)" can="admin.user.acl.edit"/>
                            @endif
                            <x-lareon::links.action type="edit" :href="route('admin.users.edit' , $user)" can="admin.user.edit"/>
                            <x-lareon::links.action type="delete" method="delete" :href="route('admin.users.destroy' , $user)" can="admin.user.delete"/>
                        </x-lareon::action-box>
                    </td>
                </tr>
            @endforeach
            <x-slot:foot>
                <tr>
                    <td colspan="9" class="p-2">
                        {!! $users->appends(request()->query())->links() !!}
                    </td>
                </tr>
            </x-slot:foot>
        </x-lareon::table>

    @endsection

</x-lareon::admin-list>
