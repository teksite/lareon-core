@props(["disabled"=>false ,'required'=>false])
<textarea @required($required) {{$disabled ? 'disabled':''}} {{$attributes->merge(['class'=>"input_check"])}} >{{$slot ?? ''}}</textarea>
