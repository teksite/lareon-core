@php

    use Lareon\Modules\Ticketing\App\Enums\SenderType;
    $isMine = $message->sender_type === $viewerType;
    $isAi = $message->sender_type === SenderType::Ai;
    $isSystem = $message->sender_type === SenderType::System;
@endphp


<div class="flex {{ $isMine ? 'justify-start' : 'justify-end' }}">
    <div class="max-w-[80%] rounded-2xl px-4 py-3 text-sm leading-6
        {{ $isSystem ? 'bg-stone-100 text-stone-500 text-xs italic w-full text-center' : '' }}
        {{ $isAi ? 'bg-indigo-50 text-indigo-900 border border-indigo-100' : '' }}
        {{ ! $isSystem && ! $isAi && $isMine ? 'bg-teal-700 text-white' : '' }}
        {{ ! $isSystem && ! $isAi && ! $isMine ? 'bg-white border border-stone-200' : '' }}
        {{ $message->is_internal_note ? 'ring-2 ring-amber-300' : '' }}">

        @unless ($isSystem)
            <div class="text-xs mb-1 opacity-70 flex items-center gap-2">
                <span>{{ $message->senderDisplayName() }}</span>
                @if ($message->is_internal_note)
                    <span class="text-amber-600 font-medium">یادداشت داخلی</span>
                @endif
            </div>
        @endunless

        <div class="whitespace-pre-line">{{ $message->body }}</div>

        @if ($message->attachments->isNotEmpty())
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach ($message->attachments as $attachment)
                    <a href="{{ $attachment->url() }}" target="_blank"
                       class="text-xs underline opacity-90 hover:opacity-100">
                        📎 {{ $attachment->original_name }} ({{ $attachment->humanSize() }})
                    </a>
                @endforeach
            </div>
        @endif

        <div class="text-[11px] mt-1 opacity-60">
            {{ $message->created_at->format('Y/m/d H:i') }}
        </div>
    </div>
</div>
