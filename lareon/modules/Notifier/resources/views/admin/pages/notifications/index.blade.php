<x-lareon::admin-list>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('notifications')]))
    @section('description', __('notifications are fixed content on a website that rarely changes and displays the same information to visitors'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.notifications.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.notification.create"/>
    @endsection
    @section('list')
        <x-lareon::table :rows="$notifications" :headers="['id'=>'#','title'=>__('title') ,'created_at'=>__('created at') ,__('read at') , __('via') , __('creator'),'']">
            @foreach($notifications as $key=>$notification)
                <tr>
                    <td class="p-3">{{$notifications->firstItem() + $key}}</td>
                    <td>{{$notification->data['message']['title'] ?? ''}}</td>
                    <td>
                        <x-lareon::date :date="$notification->created_at"/>
                    </td>
                    <td>
                        <span class="px-3 py-1 {{$notification->read_at ? 'badge-green': 'badge-yellow'}}"><x-lareon::date :date="$notification->read_at"/></span>
                    </td>
                    <td>{{implode(',',$notification->data['meta']['channels']?? [])}}</td>
                    <td>{{$notification->data['meta']['creator'] ?? ''}}</td>
                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="delete" method="delete" :href="route('admin.notifications.destroy' , $notification)" can="admin.notification.delete"/>
                        </x-lareon::action-box>
                    </td>
                </tr>
            @endforeach
                <x-slot:foot>
                    <tr>
                        <td colspan="9" class="p-2">
                            {!! $notifications->appends(request()->query())->links() !!}
                        </td>
                    </tr>
                </x-slot:foot>
        </x-lareon::table>
    @endsection

</x-lareon::admin-list>
