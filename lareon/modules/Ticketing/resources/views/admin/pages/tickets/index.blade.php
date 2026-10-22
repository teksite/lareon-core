<x-lareon::admin-list>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('tickets')]))
    @section('description', __("available tickets are tickets submitted by users to report their issues. you can help them resolve these issues by providing the necessary guidance"))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.tickets.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.ticket.create"/>
    @endsection
    @section('list')
        <div class="flex items-center gap-6 justify-between rounded-xl bordering p-3 my-6 ">
            <nav class="flex items-center justify-start overflow-x-auto gap-6">
                <span class="min-w-fit w-fit">{{__('filter')}}</span>
                <ul class="w-full flex items-center justify-start overflow-x-auto gap-3">
                    @foreach($filters as $queryString=>$label)
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['type' => $queryString]) }}" class="y-box py-1 px-2 inline-block min-w-16 font-bold text-center {{$queryString !== $filterQuery ?: 'text-blue-600'}}">{{__($label)}}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>
            <a href="{{ route('admin.tickets.referrals.index') }}" class="y-box py-1 px-2 inline-block min-w-16 font-bold text-center">{{__('referrals')}}</a>
        </div>
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
