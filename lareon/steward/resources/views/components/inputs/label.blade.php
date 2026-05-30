@props(['title'=>null , 'markAsRequire' =>false ,'for'=>null])
@if($for)
    <label {{$attributes->merge(['class'=>'input_label'])}} for="{{$for}}">
        {!! $title ?? $slot ?? '' !!}
        @if($markAsRequire)
            <span class="text-red-600 text-xs font-bold">*</span>
        @endif
    </label>

@else
    <span {{$attributes->merge(['class'=>'input_label'])}}>
        {!! $title ?? $slot ?? '' !!}
        @if($markAsRequire)
            <span class="text-red-600 text-xs font-bold">*</span>
        @endif
    </span>
@endif
