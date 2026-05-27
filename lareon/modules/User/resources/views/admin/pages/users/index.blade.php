<x-lareon::admin-layout>
    @section('title', __(':title list',['title'=>__('users')]))
    @section('description', __('in this section you can manage all users of the app'))
    @section('header.start')
{{--        <x-lareon::links.simple  :href="route('admin.users.create')" :content="__('create a new one')" color="blue" can="admin.user.create"/>--}}
    @endsection
        <x-lareon::box>
            <x-lareon::box>
                <x-lareon::table :rows="$users" :headers="['id'=>'#',__('featured image') ,'name'=>__('name'),'phone'=>__('phone'),'email'=>__('email'),'created_at'=>__('created at') ,'parent_id'=> __('creator') ,]">
                        @foreach($users as $key=>$user)
                            <tr>
                                <td class="p-3">{{$users->firstItem() + $key}}</td>
                                <td><img src="/storage/admin/avatar-default.jpg" alt="{{$user->name}}" width="45" height="45" fetchpriority="low" decoding="async" loading="lazy"></td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->phone}}</td>
                                <td>{{$user->email}}</td>
{{--                                <td>{{dateAdapter($user->created_at) ?? '-'}}</td>--}}
{{--                                <td>{{$user->parent()?->name ?? '-'}}</td>--}}
{{--
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
--}}
                            </tr>
                        @endforeach
                </x-lareon::table>
                {{$users->appends($_GET)->links()}}
            </x-lareon::box>

            {{$users->appends($_GET)->links()}}
        </x-lareon::box>

</x-lareon::admin-layout>
