@props(['item' ,'size'=>18])

@php
    $href=isset($item['route']) ? route($item['route']) : ($item['url'] ?? null);
    $active= (isset($item['active']) &&  $item['active'])
     ||(isset($item['route']) && request()->routeIs($item['route']))
     || (isset($item['url']) && str_starts_with($item['url'] , request()->url()));
@endphp

<x-icon type="{{$item['icon-type'] ?? 'outline' }}" icon="{{$item['icon'] ??  'circle'}}" high="{{$size}}" width="{{$size}}" class="fill-none stroke-gray-600"/>
@if($href)
    <a href="{{$href}}" class="{{$active ? 'text-indigo-800 font-bold' : ''}}">
        {{__($item['title'])}}
    </a>
@else
    <span class="{{$active ? 'text-indigo-800 font-bold' : ''}}">
        {{__($item['title'])}}
    </span>
@endif
