@props(['type'=>'text' ,'value'=>null , "disabled"=>false ,'required'=>false , 'readonly' =>false , 'id'=>null])
<input id="{{$id ?? ''}}" type="{{$type}}" @disabled($disabled) @readonly($readonly) @required($required) {{$attributes->merge(['class'=>"input block w-full"])}} value="{{$value}}" >
