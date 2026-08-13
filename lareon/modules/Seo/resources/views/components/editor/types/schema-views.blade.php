@props(['type'=>'WebPage' , 'data'=>[] ,'name'=> 'seo[schema]' ])
@php($view= config("seo.schema.$type" ,'web-page'))

<div data-schema-view>
    <x-dynamic-component :component="'seo::editor.types.'.$view" :name="$name" :value="$data ?? []"/>
</div>
