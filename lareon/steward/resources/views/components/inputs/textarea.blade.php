@props(["disabled"=>false ,'required'=>false])
<textarea @required($required) {{$disabled ? 'disabled':''}} {{$attributes->merge(['class'=>"input block w-full"])}} >{{$slot ?? ''}}</textarea>
