@props(['type'=>'x'])
@php
$classBase=match ($type){
    'y'=>'y-box',
    'z'=>'z-box',
    default => 'x-box'
};
@endphp
<div {{$attributes->merge(['class'=>$classBase])}}>
    {!! $slot !!}
</div>
