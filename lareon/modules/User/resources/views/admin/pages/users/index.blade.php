<x-lareon::admin-list>
    @section('title', trans('lareon::global.crud.list' ,['attribute'=>__('users')]))
    @section('description', __('in this section you can manage all users of the app'))
    @section('header.start')
        <x-lareon::links.simple :href="route('admin.users.create')" :content="__('create a new one')" color="blue" can="admin.user.create"/>
    @endsection
    @section('list')
        <x-lareon::table :rows="$users" :headers="['id'=>'#',__('featured image') ,'name'=>__('name'),'phone'=>__('phone'),'email'=>__('email'),'created_at'=>__('created at') ,'parent_id'=> __('creator') ,'']">
            @foreach($users as $key=>$user)
                <tr>
                    <td class="p-3">{{$users->firstItem() + $key}}</td>
                    <td>
                        <img src="/storage/admin/avatar-default.jpg" alt="{{$user->name}}" width="45" height="45" fetchpriority="low" decoding="async" loading="lazy">
                    </td>
                    <td>{{$user->fullname}}</td>
                    <td >{{$user->phone}}</td>
                    <td >{{$user->email}}</td>
                    <td>
                        <x-lareon::date :date="$user->created_at"/>
                    </td>
                    <td>
                        {{$user->parent()?->fullname ?? '-'}}
                    </td>
                    <td>
                        <div class="action">
                            @if(\Illuminate\Support\Facades\Route::has('admin.users.meta.edit'))
                                <x-lareon::links.action type="sub" :href="route('admin.users.meta.edit' , $user)"/>
                            @endif
                            @if($user->path())
                                <x-lareon::links.action type="show" :href="route('users.show' , $user)"/>
                            @endif
                            <x-lareon::links.action type="edit" :href="route('admin.users.edit' , $user)" can="admin.user.edit"/>
                            <x-lareon::links.action type="delete" :href="route('admin.users.destroy' , $user)" can="admin.user.delete"/>
                        </div>
                    </td>
                </tr>
            @endforeach
                <x-slot:foot>
                    <tr>
                        <td  colspan="9" class="p-2">
                            {!! $users->appends(request()->query())->links() !!}
                        </td>
                    </tr>
                </x-slot:foot>
        </x-lareon::table>

    @endsection

</x-lareon::admin-list>
