@props(['ticket'])
<section class="">
    <h2 class="text-sm font-semibold mb-3">
        {{__('reference history')}}
    </h2>

    @if ($ticket->assignments->isEmpty())
        <p class="text-sm ">
            {{__('no referral event has been recorded for this ticket yet')}}.
        </p>
    @else
        <ol class="relative border-r-2 border-line_light ps-3 space-y-3">
            @foreach ($ticket->assignments as $assignment)
                <li>
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">{{ $assignment->type->label() }}</span>
                        —
                        {{ $assignment->fromAdmin?->name ?? __('system') }}
                        ⬅
                        {{ $assignment->toAdmin?->name ?? __('admins general queue') }}
                    </div>

                    @if ($assignment->reason)
                        <div class="text-xs text-stone-500 mt-1">{{__('season')}}: {{ $assignment->reason }}</div>
                    @endif

                    <div class="text-xs mt-1">
                        {{__('status')}}:
                        <span class="font-medium">{{ $assignment->status->label() }}</span>
                        @if ($assignment->decision_reason)
                            — {{'reason for the decision'}}: {{ $assignment->decision_reason }}
                        @endif
                    </div>

                    <div class="text-gray-600 mt-1">
                        <x-lareon::date :date="$assignment->created_at"/>
                    </div>
                </li>
            @endforeach
        </ol>
    @endif
</section>
