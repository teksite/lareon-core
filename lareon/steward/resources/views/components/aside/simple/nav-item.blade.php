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

<div class="p-1">
    <a href="{{$href}}" class="hover:bg-slate-100 flex items-center gap-1 flex-col w-full bordering p-1 rounded-lg mx-auto {{$active ? 'bg-blue-100 text-blue-900' : ''}}">
        <x-icon class="stroke-current fill-none" type="{{$menu['icon-type'] ?? 'outline' }}" icon="{{$menu['icon'] ??  'circle'}}" high="{{$size}}" width="{{$size}}"/>
        <span class="text-xs font-semibold">
            {{__($menu['title'])}}
        </span>
    </a>
</div>
