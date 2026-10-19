<x-lareon::admin-layout>
    @section('title', __('ticket').' | '. $ticket->ticket_number )
    <div class="flex flex-col lg:flex-row items-start gap-6 my-12">
        <aside class="x-box w-full lg:max-w-96 space-y-6">
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
                {{__('priority')}}: <x-ticketing::priority-pulse :priority="$ticket->priority"/>
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
        <div class="y-box w-full">
            <ul class="space-y-6 max-w-5xl mx-auto overflow-y-auto max-h-svh">
                @foreach($ticket->messages  ?? [] as $message)
                    @php
                        $isUser = $message->sender_type === \Lareon\Modules\Ticketing\App\Enums\SenderType::User;
                    @endphp
                    <li>
                        <div class="flex gap-3 items-center {{$isUser ?:'flex-row-reverse'}}">
                            <span class="{{$isUser ? 'bg-green-600' : 'bg-blue-600'}} flex items-center justify-center rounded-full p-1 h-9 w-9 text-white  font-bold text-sm">{{\Illuminate\Support\Str::initials($ticket->user->name)}}</span>
                            <div class="shadow p-6 rounded-lg w-full max-w-5xl {{$isUser ? 'bg-green-50' : 'bg-blue-50'}}">
                                {{$message->body}}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>


</x-lareon::admin-layout>
