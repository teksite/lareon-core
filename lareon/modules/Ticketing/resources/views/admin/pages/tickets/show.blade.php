<x-lareon::admin-layout>
    @section('title', $ticket->ticket_number)

    @section('header.start')
        <x-lareon::links.nav :href="route('admin.tickets.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('tickets')])" color="index" can="admin.ticket.read"/>
    @endsection

    @section('header.end')
        @if ($isHolder)
            <div class="flex justify-end">
                <x-lareon::links.nav :href="route('admin.tickets.refer.create', $ticket)" :content="__('assign this ticket to another admin')" variant="simple" color="show" can="admin.ticket.read"/>
            </div>
        @endif
    @endsection

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
                این تیکت به شما ارجاع داده شده و در انتظار تصمیم شماست. برای پذیرفتن یا رد آن،
                به
                <a href="{{ route('admin.tickets.referrals.inbox') }}" class="underline font-medium">صندوق ارجاعات من</a> بروید.
            </div>
        @elseif ($ticket->isClosed())
            <div class="rounded-lg border border-stone-200 bg-stone-100 text-stone-500 text-sm px-4 py-3 text-center">
                این تیکت بسته شده است.
            </div>
        @elseif ($isHolder)
            <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}"
                  enctype="multipart/form-data" class="bg-white border border-stone-200 rounded-xl p-5 space-y-4">
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
                    یادداشت داخلی (فقط برای ادمین‌ها قابل مشاهده است، برای کاربر ارسال نمی‌شود)
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

        <section class="mt-10">
            <h2 class="text-sm font-semibold text-stone-500 mb-3">تاریخچه‌ی ارجاعات</h2>

            @if ($ticket->assignments->isEmpty())
                <p class="text-sm text-stone-400">هنوز رخداد ارجاعی برای این تیکت ثبت نشده است.</p>
            @else
                <ol class="relative border-r-2 border-stone-200 pr-4 space-y-4">
                    @foreach ($ticket->assignments as $assignment)
                        <li>
                            <div class="text-sm text-stone-700">
                                <span class="font-medium">{{ $assignment->type->label() }}</span>
                                —
                                {{ $assignment->fromAdmin?->name ?? 'سیستم' }}
                                ⬅
                                {{ $assignment->toAdmin?->name ?? 'صف عمومی ادمین‌ها' }}
                            </div>

                            @if ($assignment->reason)
                                <div class="text-xs text-stone-500 mt-0.5">دلیل: {{ $assignment->reason }}</div>
                            @endif

                            <div class="text-xs mt-0.5">
                                وضعیت:
                                <span class="font-medium">{{ $assignment->status->label() }}</span>
                                @if ($assignment->decision_reason)
                                    — دلیل تصمیم: {{ $assignment->decision_reason }}
                                @endif
                            </div>

                            <div class="text-[11px] text-stone-400 mt-0.5">
                                {{ $assignment->created_at->format('Y/m/d H:i') }}
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </section>
    </div>

</x-lareon::admin-layout>
