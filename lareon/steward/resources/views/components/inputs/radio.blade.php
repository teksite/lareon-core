@props(['checked'=>false,'value'=>null , "disabled"=>false ,'required'=>false])
<input type="radio" @checked($checked) @required($required) {{$disabled ? 'disabled':''}} {{$attributes->merge(['class'=>"input_check"])}} value="{{$value}}" >
