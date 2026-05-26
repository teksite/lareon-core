<x-lareon::admin-list-layout>
    @section('title', __(':title list',['title'=>__('users')]))
    @section('description', __('in this section you can manage all users of the app'))
    @section('header.start')
        <x-lareon::link.btn-outline :href="route('admin.users.create')" :title="__('create a new one')" color="create" can="admin.user.create"/>
    @endsection
    @section('list')
        <x-lareon::box>
            <x-lareon::table :headers="['id'=>'#',__('featured image') ,'name'=>__('name'),'phone'=>__('phone'),'email'=>__('email'),'created_at'=>__('created at') ,'parent_id'=> __('creator') ,]">
                @if(count($users))
                @foreach($users as $key=>$user)
                    <tr>
                        <td class="p-3">{{$users->firstItem() + $key}}</td>
                        <td><img src="/storage/admin/avatar-default.jpg" alt="{{$user->name}}" width="45" height="45" fetchpriority="low" decoding="async" loading="lazy"></td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->phone}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{dateAdapter($user->created_at) ?? '-'}}</td>
                        <td>{{$user->parent()?->name ?? '-'}}</td>
                        <td>
                            <div class="action">
                                    @if(\Illuminate\Support\Facades\Route::has('admin.users.meta.edit'))
                                        <x-lareon::link.sub :href="route('admin.users.meta.edit' , $user)"/>
                                    @endif
                                    @if($user->path())
                                        <x-lareon::link.show :href="route('users.show' , $user)"/>
                                    @endif
                                <x-lareon::link.edit :href="route('admin.users.edit' , $user)" can="admin.user.edit"/>
                                <x-lareon::link.delete :href="route('admin.users.destroy' , $user)" can="admin.user.delete"/>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="6">
                            <p class="text-center">
                                {{__('no item has been found')}}.
                            </p>
                        </td>
                    </tr>
                @endif
            </x-lareon::table>
            {{$users->appends($_GET)->links()}}
        </x-lareon::box>
    @endsection

</x-lareon::admin-list-layout>
