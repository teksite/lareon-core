@props(['type'=>'date' ,'value'=>null , "disabled"=>false ,'required'=>false,'min' => null,'max' => null,'step' => null,])
@php
    $formats = [
        'date' => 'Y-m-d',
        'time' => 'H:i',
        'datetime-local' => 'Y-m-d\TH:i',
        'month' => 'Y-m',
        'week' => 'Y-\WW',
    ];

    if ($value && isset($formats[$type]) && ($value instanceof \DateTime || $value instanceof \Carbon\Carbon)) {
        $value = $value->format($formats[$type]);
    }
@endphp
<input type="{{$type}}" @required($required) {{$disabled ? 'disabled':''}} {{$attributes->merge(['class'=>"input block w-full"])}} value="{{$value}}" @if($min) min="{{ $min }}" @endif @if($max) max="{{ $max }}" @endif @if($step) step="{{ $step }}" @endif>
