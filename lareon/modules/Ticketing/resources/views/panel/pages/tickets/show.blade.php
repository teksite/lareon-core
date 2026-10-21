<x-lareon::panel-layout>
    @section('title', __('ticket').' | '. $ticket->ticket_number )

    <div class="inner-content flex flex-col items-start gap-6 md:flex-row">
        <div class="w-full md:max-w-[350px]">
            @include('ticketing::panel.pages.tickets.partials.nav' , ['ticketId' => $ticket->isClosed() ? null : $ticket->ticket_number])
        </div>

        <div class="w-full">
            <section class="w-full y-box">
                <div class="flex items-center justify-center gap-3">
                    <h2>{{$ticket->subject}}</h2>
                    <x-ticketing::priority-pulse :priority="$ticket->priority"/>
                    <span class="text-xs px-2 py-1 rounded-full text-white" style="background-color: {{ $ticket->status->color() }}">
                          {{ $ticket->status->label() }}
                    </span>
                </div>
                <hr class="bordering my-6 ">
                <div class="max-w-5xl mx-auto ">
                    <x-ticketing::editor.messages-list :ticket="$ticket"/>
                </div>
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
                                {{ __('closing the ticket') }}
                            </x-lareon::buttons.nav>
                        </div>
                    </form>

                </section>
            @endif
        </div>

    </div>


</x-lareon::panel-layout>
