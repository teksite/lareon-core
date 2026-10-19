<x-lareon::admin-layout>

    @section('title', $ticket->ticket_number)

        <a href="{{ route('admin.tickets.index') }}" class="hover:text-teal-700">داشبورد</a>
        <a href="{{ route('admin.tickets.referrals.inbox') }}" class="hover:text-teal-700">ارجاعات من</a>

        <div class="flex items-start justify-between gap-4 mb-2">
            <div>
                <div class="text-xs text-stone-400 mb-1">
                    {{ $ticket->ticket_number }} · کاربر: {{ $ticket->user?->name ?? 'کاربر حذف‌شده' }}
                </div>
                <h1 class="text-xl font-bold">{{ $ticket->subject }}</h1>
            </div>
            <div class="flex items-center gap-2 shrink-0">
            <span class="text-xs px-2 py-1 rounded-full text-white" style="background-color: {{ $ticket->priority->color() }}">
                {{ $ticket->priority->label() }}
            </span>
                <span class="text-xs px-2 py-1 rounded-full text-white" style="background-color: {{ $ticket->status->color() }}">
                {{ $ticket->status->label() }}
            </span>
            </div>
        </div>

        @if ($isHolder)
            <div class="flex justify-end mb-6">
                <a href="{{ route('admin.tickets.refer.create', $ticket) }}"
                   class="text-sm text-teal-700 hover:text-teal-900 underline">
                    ارجاع این تیکت به ادمین دیگر
                </a>
            </div>
        @endif

        <div class="space-y-3 mb-6">
            @php $viewerType = \Lareon\Modules\Ticketing\App\Enums\SenderType::Admin; @endphp
            @foreach ($ticket->messages as $message)
                @include('ticketing::partials.message', ['message' => $message, 'viewerType' => $viewerType])
            @endforeach
        </div>

        @if ($isPendingDecision)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-sm text-amber-900">
                این تیکت به شما ارجاع داده شده و در انتظار تصمیم شماست. برای پذیرفتن یا رد آن،
                به <a href="{{ route('admin.tickets.referrals.inbox') }}" class="underline font-medium">صندوق ارجاعات من</a> بروید.
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
                    <label for="body" class="block text-sm font-medium mb-1">پاسخ شما</label>
                    <textarea id="body" name="body" rows="4" required minlength="1" maxlength="10000"
                              class="w-full rounded-lg border border-stone-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-600"></textarea>
                </div>

                @if (config('ticketing.attachments.enabled'))
                    <input type="file" name="attachments[]" multiple
                           class="w-full text-sm text-stone-600 file:ml-3 file:rounded-lg file:border-0 file:bg-stone-100 file:px-3 file:py-2">
                @endif

                <label class="flex items-center gap-2 text-sm text-stone-600">
                    <input type="checkbox" name="is_internal_note" value="1" class="rounded border-stone-300">
                    یادداشت داخلی (فقط برای ادمین‌ها قابل مشاهده است، برای کاربر ارسال نمی‌شود)
                </label>

                <div class="flex items-center justify-between">
                    <button type="submit"
                            class="rounded-lg bg-teal-700 text-white text-sm font-medium px-5 py-2.5 hover:bg-teal-800">
                        ارسال
                    </button>

                    <button type="button" class="text-sm text-rose-600 hover:text-rose-800 js-confirm-close"
                            data-confirm-target="close-ticket-form">
                        بستن تیکت
                    </button>
                </div>
            </form>

            <form id="close-ticket-form" method="POST" action="{{ route('admin.tickets.close', $ticket) }}" class="hidden">
                @csrf
            </form>
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

        @push('footerScripts')
            <script>
                document.querySelectorAll('.js-confirm-close').forEach(function (button) {
                    button.addEventListener('click', function () {
                        if (confirm('آیا از بستن این تیکت مطمئن هستید؟')) {
                            document.getElementById(button.dataset.confirmTarget)?.submit();
                        }
                    });
                });
            </script>
        @endpush

</x-lareon::admin-layout>
