@props(['name' , 'value'=>[], "inline"=>false, "required"=>true ,'labelPosition' => 'start'])
@php
    $range=[
    "$"=>"cheap",
    "$$"=>"moderate pricing",
    "$$$"=>"expensive",
    "$$$$"=>"very expensive",
];
@endphp
<x-lareon::editor.input-select :inline="$inline" :required="$required" :label="__('price range')" name="{{$name}}" :value="$value" :labelPosition="$labelPosition">
    @foreach($range as $key=>$desc)
        <option value="{{$key}}">
            {{__($desc)}}
        </option>
    @endforeach
</x-lareon::editor.input-select>
