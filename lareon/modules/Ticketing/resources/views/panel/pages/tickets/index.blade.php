<x-lareon::panel-layout>
    @section('title', __('lareon::global.crud.titles.list', [ 'attribute' => __('tickets'), ]))

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold">تیکت‌های من</h1>
        <a href="{{ route('panel.tickets.create') }}"
           class="inline-flex items-center gap-1 rounded-lg bg-teal-700 text-white text-sm px-4 py-2 hover:bg-teal-800">
            ثبت تیکت جدید
        </a>
    </div>


    <div class="p-3 bordering rounded-xl my-12 flex items-center justify-start gap-3">
        @foreach($options as $key=>$option)
            <a href="?status={{$key}}" class="bg-white rounded-lg w-24 min-w-fit py-1 px-3 text-xs shadow text-center    {{ request()->has('read') && request()->integer('read') === 0        ? 'text-blue-600 font-bold'        : 'text-gray-600' }}">
                {{ __(\Illuminate\Support\Str::plural($option)) }}
            </a>
        @endforeach
    </div>


    <div class="rounded-xl border border-stone-200 bg-white divide-y divide-stone-100 overflow-hidden">
        @foreach ($tickets as $ticket)
            <a href="{{ route('ticketing.user.tickets.show', $ticket) }}"
               class="flex items-center justify-between gap-4 px-4 py-4 hover:bg-stone-50">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 text-xs text-stone-400 mb-1">
                        <span>{{ $ticket->ticket_number }}</span>
                        <span>·</span>
                        <span>{{ $ticket->created_at->format('Y/m/d H:i') }}</span>
                    </div>
                    <div class="font-medium text-stone-800 truncate">{{ $ticket->subject }}</div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                        <span class="text-xs px-2 py-1 rounded-full text-white"
                              style="background-color: {{ $ticket->priority->color() }}">
                            {{ $ticket->priority->label() }}
                        </span>
                    <span class="text-xs px-2 py-1 rounded-full text-white"
                          style="background-color: {{ $ticket->status->color() }}">
                            {{ $ticket->status->label() }}
                        </span>
                </div>
            </a>
        @endforeach
    </div>

</x-lareon::panel-layout>
