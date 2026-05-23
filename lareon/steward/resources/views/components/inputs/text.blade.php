@props(['type'=>'text' ,'value'=>null , "disabled"=>false ,'required'=>false])
<input type="{{$type}}" @required($required) {{$disabled ? 'disabled':''}} {{$attributes->merge(['class'=>"input block w-full"])}} value="{{$value}}" >
