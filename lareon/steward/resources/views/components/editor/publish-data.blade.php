@props(['instance'])
@php
    $types=[
        'created_at'=>'created at',
        'updated_at'=>'updated at',
        'published_at'=>'published at',
        'deleted_at'=>'deleted at',
        'read_at'=>'read at',
];
    $colors=[
        'created_at'=>'text-green-600',
        'updated_at'=>'text-blue-600',
        'published_at'=>'text-teal-600',
        'deleted_at'=>'text-red-600',
        'read_at'=>'text-red-600',
        'default'=>'text-gray-600',
]

@endphp


@if($instance)
    <ul class="space-y-6">
        @foreach($types as $col=>$title)
            @if($instance->$col)
                <li class="flex items-center gap-3 justify-between text-xs">
                    <span class="font-bold  min-w-fit  {{$colors[$col] ?? $colors['default']}}">
                        {{__($title)}}
                    </span>
                    <hr class="border-dotted w-full">
                    <span class="min-w-fit">
                          <x-lareon::date :date="$instance->$col"/>
                    </span>
                </li>
            @endif
        @endforeach
    </ul>

@endif
