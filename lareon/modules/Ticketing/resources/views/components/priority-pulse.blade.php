@props(['priority'])
@php
    $color = $priority->color();

    $pulse = in_array($priority, [
        \Lareon\Modules\Ticketing\App\Enums\PriorityType::Low,
        \Lareon\Modules\Ticketing\App\Enums\PriorityType::Normal,
        \Lareon\Modules\Ticketing\App\Enums\PriorityType::High,
        \Lareon\Modules\Ticketing\App\Enums\PriorityType::Urgent,
    ], true);
@endphp

<span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-medium" style="color: {{ $color }}; background-color: color-mix(in srgb, {{ $color }} 12%, transparent);">
    <span class="relative flex size-2">
        @if ($pulse)
            <span class="absolute inline-flex size-full animate-ping rounded-full opacity-75" style="background-color: {{ $color }}"></span>
        @endif
        <span class="relative inline-flex size-2 rounded-full" style="background-color: {{ $color }}"></span>
    </span>

    {{ $priority->label() }}
</span>
