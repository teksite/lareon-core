<x-lareon::panel-layout>

    {{--    type="update" method="post" :instance="$ticket" :action="route('panel.tickets.reply')" :hasTab="false" --}}
    @section('title', __('ticket').' | '. $ticket->ticket_number )

    <section class="max-w-5xl mx-auto y-box">
        <div class="flex items-center justify-center gap-3">
            <h2>{{$ticket->subject}}</h2>
            <x-ticketing::priority-pulse :priority="$ticket->priority"/>
            <span class="text-xs px-2 py-1 rounded-full text-white" style="background-color: {{ $ticket->status->color() }}">
                {{ $ticket->status->label() }}
            </span>
        </div>
        <hr class="bordering my-6 ">
        <ul class="space-y-6 max-w-5xl mx-auto overflow-y-auto max-h-svh">
            @foreach($ticket->messages  ?? [] as $message)
                @php
                    $isOwner = $user->id === $message->sender_id && $message->sender_type === \Lareon\Modules\Ticketing\App\Enums\SenderType::User;
                @endphp
                <li>
                    <div class="flex gap-3 items-center {{$isOwner ?:'flex-row-reverse'}}">
                        <span class="{{$isOwner ? 'bg-green-600' : 'bg-blue-600'}} flex items-center justify-center rounded-full p-1 h-9 w-9 text-white  font-bold text-sm">{{\Illuminate\Support\Str::initials($user->name)}}</span>
                        <div class="shadow p-6 rounded-lg w-full max-w-5xl {{$isOwner ? 'bg-green-50' : 'bg-blue-50'}}">
                            {{$message->body}}
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </section>

    @if ($ticket->isClosed())
        <div class="mt-12 max-w-5xl mx-auto rounded-lg bordering bg-stone-100 text-gray-500 text-sm px-4 py-3 text-center">
            {{__('the ticket is closed.')}}
        </div>
    @else
        <section class="y-box mt-12 max-w-5xl mx-auto">
            <form action="{{route('panel.tickets.reply' ,$ticket)}}" method="POST" class="space-y-6">
                @csrf
                <x-lareon::editor.input-textarea :required="true" labelPosition="top" :label="__('message')" name="body" rows="8" min="5" max="10000"></x-lareon::editor.input-textarea>
                @if (config('ticketing.attachments.enabled'))
                    <div>
                        <input type="file" name="attachments[]" multipleclass="w-full text-sm text-gray-600 file:ms-3 file:rounded-lg file:border-0 file:bg-gray-100 file:px-3 file:py-2">
                    </div>
                @endif
                <div class="flex items-center justify-between gap-5">
                    <x-lareon::buttons.nav class="min-w-36 mt-6" :fullWidth="false" type="submit" color="update" size="xs">
                        {{ __('send') }}
                    </x-lareon::buttons.nav>
                    <x-lareon::buttons.nav data-confirm-target="close-ticket-form" class="min-w-36 mt-6 js-confirm-close" :fullWidth="false" type="button" color="delete" variant="outline" size="xs">
                        {{ __('close') }}
                    </x-lareon::buttons.nav>
                </div>
            </form>
            <form id="close-ticket-form"  method="POST" action="{{ route('panel.tickets.close', $ticket) }}" class="hidden">
                @csrf
            </form>
        </section>
    @endif
    @push('footerScripts')
        <script>
            document.querySelectorAll('.js-confirm-close').forEach(function (button) {
                button.addEventListener('click', function () {
                    if (confirm('{{__('are you sure you want to close this ticket? You will not be able to send any further replies after closing it.')}}' )) {
                        document.getElementById(button.dataset.confirmTarget)?.submit();
                    }
                });
            });
        </script>
    @endpush
</x-lareon::panel-layout>
