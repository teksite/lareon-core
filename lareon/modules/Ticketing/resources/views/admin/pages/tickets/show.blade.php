<x-lareon::admin-layout>
    @section('title', $ticket->ticket_number)

    @section('header.start')
        <x-lareon::links.nav :href="route('admin.tickets.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('tickets')])" color="index" can="admin.ticket.read"/>
    @endsection

    @section('header.end')
        @if ($isHolder && !$ticket->isClosed())
            <div class="flex justify-end">
                <x-lareon::links.nav x-data @click="$dispatch('open-modal', 'page-content')"  :content="__('assign this ticket to another admin')" variant="simple" color="show" can="admin.ticket.read"/>
            </div>
        @endif
    @endsection
    <x-lareon::modal name="page-content" maxWidth="5xl">
        <h3 class="text-center mb-3">{{__('referring the ticket')}}</h3>
        <form action="{{route('admin.tickets.refer.store' ,$ticket)}}" method="post">
            @csrf
            <x-ticketing::editor.create-refer :ticket="$ticket" />
            <div class="mt-6">
                <x-lareon::buttons.nav class="min-w-36" :fullWidth="false" type="submit" color="blue">
                    {{ __('refer') }}
                </x-lareon::buttons.nav>
            </div>
        </form>
    </x-lareon::modal>

    <div class="max-w-5xl mx-auto ">
        <h2 class="text-center">{{$ticket->subject}}</h2>
        <div class="flex items-start justify-between gap-3 mb-3 bordering p-2 rounded-lg my-3">
            <div>
                <div class="text-gray-600 mb-1">
                    {{ $ticket->ticket_number }} | {{__('user')}}: {{ $ticket->user?->name ?? __('removed user') }}
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
            <span class="text-xs px-2 py-1 rounded-full text-white font-bold" style="background-color: {{ $ticket->priority->color() }}">
                {{ $ticket->priority->label() }}
            </span>
                <span class="text-xs px-2 py-1 rounded-full text-white font-bold" style="background-color: {{ $ticket->status->color() }}">
                {{ $ticket->status->label() }}
            </span>
            </div>
        </div>
        <x-ticketing::editor.messages-list :ticket="$ticket" :showInternal="true"/>

        @if ($isPendingDecision)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-sm text-amber-900">
                {{__('this ticket has been assigned to you and is awaiting your decision. please go to the assignments page to accept or reject it')}}.
                <a href="{{ route('admin.tickets.referrals.index') }}#ticket_{{$ticket->id}}" class="underline font-medium">{{__('refers')}}</a>
            </div>
        @elseif ($ticket->isClosed())
            <div class="rounded-lg border border-stone-200 bg-stone-100 text-stone-500 text-sm px-4 py-3 text-center">
                {{__('the ticket is closed')}}.
            </div>
        @elseif ($isHolder)
            <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}"
                  enctype="multipart/form-data" class="x-box bordering mt-6 rounded-xl  space-y-4">
                @csrf

                <div>
                    <x-lareon::editor.input-textarea :required="true" labelPosition="top" :label="__('reply')" name="body" rows="8" min="5" max="10000"></x-lareon::editor.input-textarea>
                </div>

                @if (config('ticketing.attachments.enabled'))
                    <input type="file" name="attachments[]" multiple
                           class="w-full text-sm text-stone-600 file:ml-3 file:rounded-lg file:border-0 file:bg-stone-100 file:px-3 file:py-2">
                @endif

                <label class="flex items-center gap-2 text-sm text-stone-600">
                    <input type="checkbox" name="is_internal_note" value="1" class="rounded border-stone-300">
                    {{__('internal note (visible only to admins; not sent to the user)')}}
                </label>

                <div class="flex items-center justify-between gap-5">
                    <x-lareon::buttons.nav class="min-w-36 mt-6" :fullWidth="false" type="submit" color="update" size="xs">
                        {{ __('send') }}
                    </x-lareon::buttons.nav>
                    <x-lareon::buttons.nav commandfor="closeDialog" command="show-modal" data-confirm-target="close-ticket-form" class="min-w-36 mt-6 js-confirm-close" :fullWidth="false" type="button" color="delete" variant="outline" size="xs">
                        {{ __('close') }}
                    </x-lareon::buttons.nav>
                </div>
            </form>

            <dialog id="closeDialog" class="y-box translate-x-1/2 top-1/2 inset-x-1/2 w-full max-w-100">
                <div class="relative">
                    <form id="close-ticket-form" method="POST" action="{{ route('admin.tickets.close', $ticket) }}">
                        @csrf
                        <x-lareon::editor.input-textarea :required="true" placeholder="why?" labelPosition="top" :label="__('note')" name="note" rows="8" min="5" max="10000"></x-lareon::editor.input-textarea>
                        <x-lareon::buttons.nav class="min-w-36 mt-6" :fullWidth="false" type="submit" color="update" size="xs">
                            {{ __('submit') }}
                        </x-lareon::buttons.nav>
                    </form>
                    <button commandfor="closeDialog" command="close" class="absolute top-0 left-0 text-red-600 font-bold">
                        X
                    </button>
                </div>
            </dialog>

        @endif

        <section class="mt-6">
            <x-ticketing::editor.messages-history :ticket="$ticket"/>
        </section>
    </div>

</x-lareon::admin-layout>
