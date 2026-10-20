<x-lareon::admin-list>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('tickets')]))
    @section('description', __('tickets are fixed content on a website that rarely changes and displays the same information to visitors'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.ticketing.tickets.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.ticket.create"/>
    @endsection
    @section('list')
        <nav class="rounded-xl bordering p-3 my-6 flex items-center justify-start overflow-x-auto gap-6">
            <span class="min-w-fit w-fit">{{__('filter')}}</span>
            <ul class="w-full flex items-center justify-start overflow-x-auto gap-3">
                @foreach($filters as $queryString=>$label)
                    <li>
                        <a href="{{ request()->fullUrlWithQuery(['type' => $queryString]) }}" class="y-box py-1 px-2 inline-block min-w-16 font-bold text-center {{$queryString !== $filterQuery ?: 'text-blue-600'}}">{{$label}}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
        <x-lareon::table :rows="$tickets" :headers="['id'=>'#','ticket_number'=>__('ticket number') ,__('subject') ,'created_at'=>__('created at') ,__('status'),__('priority'),'']">
            @foreach($tickets as $key=>$ticket)
                <tr class="{{$ticket->status === \Lareon\Modules\Ticketing\App\Enums\TicketStatusType::Closed ?: 'font-bold'}}">
                    <td class="p-3">{{$tickets->firstItem() + $key}}</td>
                    <td class="p-3">{{$ticket->ticket_number}}</td>

                    <td>{{$ticket->subject}}</td>
                    <td>
                        <x-lareon::date :date="$ticket->created_at"/>
                    </td>
                    <td>
                       <span class="text-xs" style="color: {{ $ticket->status->color() }}">
                             {{ $ticket->status->label() }}
                       </span>
                    </td>
                    <td>
                        <span class="font-bold" style="color: {{ $ticket->priority->color() }}">
                            {{ $ticket->priority->label() }}
                        </span>
                    </td>

                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="show" :href="route('admin.tickets.show', $ticket)" can="admin.ticket.reply"/>
                            <x-lareon::links.action type="delete" method="delete" :href="route('admin.ticketing.tickets.destroy' , $ticket)" can="admin.ticket.delete"/>
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
