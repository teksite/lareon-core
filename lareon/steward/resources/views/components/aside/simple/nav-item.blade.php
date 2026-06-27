@props(['menu' ,'size'=>24])
@php
    $route = $menu['route'] ?? null;
    $url = $menu['url'] ?? null ;
    $preActivation = isset($menu['active']) && $menu['active'];
    $href=$route ? route($route) : $url;
    $active= $preActivation
     ||($route && request()->routeIs($route)
     || ($url && str_starts_with($url , request()->url())));

@endphp

<div>
    <a href="{{$href}}" class="hover:bg-slate-100 flex items-center gap-1 w-full p-1 mx-auto {{$active ? 'bg-blue-100 text-blue-900' : ''}}">
       <span class="bordering rounded-full p-1 shadow-lg bg-slate-50">
            <x-icon class="stroke-current fill-none " type="{{$menu['icon-type'] ?? 'outline' }}" icon="{{$menu['icon'] ??  'circle'}}" high="{{$size}}" width="{{$size}}"/>
       </span>
        <span class="text-xs font-semibold">
            {{__($menu['title'])}}
        </span>
    </a>
</div>
