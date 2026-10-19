<x-lareon::admin-list>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('tickets')]))
    @section('description', __('tickets are fixed content on a website that rarely changes and displays the same information to visitors'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.tickets.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.ticket.create"/>
        <x-lareon::links.nav :href="route('admin.tickets.trash.index')" :content="$trashCount" color="trash" can="admin.ticket.delete"/>
    @endsection
    @section('list')
        <x-lareon::table :rows="$tickets" :headers="['id'=>'#','ticket_number'=>__('ticket number') ,__('subject') ,'created_at'=>__('created at') ,__('status'),__('priority'),'']">
            @foreach($tickets as $key=>$ticket)
                <tr>
                    <td class="p-3">{{$tickets->firstItem() + $key}}</td>
                    <td class="p-3">{{$ticket->ticket_number}}</td>

                    <td>{{$ticket->subject}}</td>
                    <td>
                        <x-lareon::date :date="$ticket->created_at"/>
                    </td>
                    <td>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-xs px-2 py-1 rounded-full text-white" style="background-color: {{ $ticket->priority->color() }}">
                                {{ $ticket->priority->label() }}
                            </span>
                            <span class="text-xs px-2 py-1 rounded-full text-white" style="background-color: {{ $ticket->status->color() }}">
                                  {{ $ticket->status->label() }}
                            </span>
                        </div>
                    </td>

                    <td>
                        <x-lareon::action-box class="action">
                            @if(\Illuminate\Support\Facades\Route::has('admin.tickets.meta.edit'))
                                <x-lareon::links.action type="sub" :href="route('admin.tickets.meta.edit' , $ticket)"/>
                            @endif
                            @if($ticket->path())
                                <x-lareon::links.action type="show" :href="$ticket->path()"/>
                            @endif
                            @if(\Illuminate\Support\Facades\Route::has('admin.tickets.acl.edit'))
                                <x-lareon::links.action type="setting" :href="route('admin.tickets.acl.edit' , $ticket)" can="admin.ticket.acl.edit"/>
                            @endif
                            <x-lareon::links.action type="edit" :href="route('admin.tickets.edit' , $ticket)" can="admin.ticket.edit"/>
                            <x-lareon::links.action type="delete" method="delete" :href="route('admin.tickets.destroy' , $ticket)" can="admin.ticket.delete"/>
                        </x-lareon::action-box>
                    </td>
                </tr>
            @endforeach
            <x-slot:foot>
                <tr>
                    <td colspan="9" class="p-2">
                        {!! $tickets->appends(request()->query())->links() !!}
                    </td>
                </tr>
            </x-slot:foot>
        </x-lareon::table>

    @endsection

</x-lareon::admin-list>
