@props(['instance'])
@php
    $types=[
        'created_at'=>'created at',
        'updated_at'=>'updated at',
        'published_at'=>'published at',
        'deleted_at'=>'deleted at',
        'seen_at'=>'seen at',
        'read_at'=>'read at',
];
    $colors=[
        'created_at'=>'text-green-600',
        'updated_at'=>'text-blue-600',
        'published_at'=>'text-yellow-600',
        'deleted_at'=>'text-red-600',
        'read_at'=>'text-violet-600',
        'seen'=>'text-amber-600',
        'default'=>'text-gray-600',
];
    $icons=[
        'created_at'=>'database',
        'updated_at'=>'adjustment-two',
        'published_at'=>'calender',
        'deleted_at'=>'trash-opened',
        'read_at'=>'tick-double',
        'seen_at'=>'eye',
        'default'=>'circle-three',
];
@endphp


@if($instance)
    <ul class="space-y-6">
        @foreach($types as $col=>$title)
            @if($instance->$col)
                <li class="flex items-center gap-3 justify-between text-xs">
                    <div class="font-bold inline-flex items-center gap-2  min-w-fit  {{$colors[$col] ?? $colors['default']}}">
                        <x-icon type="outline" icon="{{$icons[$col] ?? $icons['default']}}" size="14" class="fill-none stroke-current"/>
                        <span>
                            {{__($title)}}
                        </span>
                    </div>
                    <hr class="border-dotted w-full">
                    <div class="min-w-fit">
                        <x-lareon::date :date="$instance->$col"/>
                    </div>
                </li>
            @endif
        @endforeach
    </ul>
@endif
