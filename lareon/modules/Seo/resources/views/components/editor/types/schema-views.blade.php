@props(['data'=>[] , 'name'=> 'seo[schema]'])
@php
    $file= (config('seo.schema',[]))[$data['type'] ?? 'WebPage'] ?? 'web-page'
@endphp
<div data-schema-view>
    <x-dynamic-component :component="'seo::editor.types.' . $file" :name="$name" :value="$data['schema'] ?? []"/>
</div>
