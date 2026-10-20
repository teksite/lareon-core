<x-lareon::admin-layout>
    @section('title', __('ticket').' | '. $ticket->ticket_number )
    <div class="flex flex-col lg:flex-row items-start gap-3 my-12">
        <aside class="y-box w-full lg:max-w-96 space-y-6">
            <div>
                <span class="font-bold"> {{ $ticket->ticket_number}}</span>
            </div>
            <div>
                {{__('subject')}}: {{$ticket->subject}}
            </div>
            <div class="">
                {{__('user')}}: {{$ticket->user->name}}
            </div>
            <div>
                {{__('priority')}}:
                <x-ticketing::priority-pulse :priority="$ticket->priority"/>
            </div>
            <div>
                {{__('status')}}:
                <span class="inline-block text-xs px-2 py-1 rounded-full text-white" style="background-color: {{ $ticket->status->color() }}">
                  {{ $ticket->status->label() }}
                </span>
            </div>

            <div>
                {{__('owner')}}: {{$ticket->holderAdmin?->name ?? __('unassigned')}}
            </div>
        </aside>
        <div class="mx-auto w-full">
            <x-ticketing::editor.messages-list :ticket="$ticket"  :showInternal="true"/>
        </div>
    </div>


</x-lareon::admin-layout>
