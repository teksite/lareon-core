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
    <a href="{{$href}}" class="hover:bg-slate-100 flex items-center gap-1 w-full p-1 mx-auto rounded-2xl {{$active ? 'bg-second_color_light text-second_color_dark' : ''}}">
       <span class="bordering rounded-xl p-1 shadow-lg bg-slate-50">
            <x-tkicon class="stroke-current fill-none " type="{{$menu['icon-type'] ?? 'outline' }}" icon="{{$menu['icon'] ??  'circle'}}" high="{{$size}}" width="{{$size}}"/>
       </span>
        <span class="text-xs font-semibold">
            {{__($menu['title'])}}
        </span>
    </a>
</div>
