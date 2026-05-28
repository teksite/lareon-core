@props(['menu' ,'size'=>18])
@php
    $route = $menu['route'] ?? null;
    $url = $menu['url'] ?? null ;
    $preActivation = isset($menu['active']) && $menu['active'];
    $href=$route ? route($route) : $url;

    $active= $preActivation
     ||($route && request()->routeIs($route)
     || ($url && str_starts_with($url , request()->url())));
@endphp

<x-icon type="{{$menu['icon-type'] ?? 'outline' }}" icon="{{$menu['icon'] ??  'circle'}}" high="{{$size}}" width="{{$size}}" :class="$active ? 'fill-none stroke-indigo-800' : 'fill-none stroke-gray-600'"/>
@if($href)
    <a href="{{$href}}" class="{{$active ? 'text-indigo-800 font-bold' : ''}}">
        {{__($menu['title'])}}
    </a>
@else
    <span class="{{$active ? 'text-indigo-800 font-bold' : ''}}">
        {{__($menu['title'])}}
    </span>
@endif
