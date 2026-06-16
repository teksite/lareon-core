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

<div class="group p-1">
    <button type="button" role="button" class="hover:bg-slate-100 flex items-center gap-1 flex-col w-full bordering p-1 rounded-lg mx-auto {{$active ? 'bg-blue-100 text-blue-900' : ''}}">
        <x-icon class="stroke-current fill-none" type="{{$menu['icon-type'] ?? 'outline' }}" icon="{{$menu['icon'] ??  'circle'}}" high="{{$size}}" width="{{$size}}"/>
        <span class="text-xs font-semibold">
        {{__($menu['title'])}}
    </span>
    </button>
    <div class="group-hover:block hidden absolute z-10 -end-48  top-1 bottom-0 h-[99%] w-48 bg-slate-50 rounded-e-lg bordering">
        <span class="block text-center font-bold text-sm py-2">
            {{__($menu['title'])}}
        </span>
        <hr class="bordering my-1">
        <ul class="space-y-3">
            @foreach($menu['children'] ?? [] as $child)
                <li>
                    <x-lareon::aside.item-link :menu="$child" size="10"/>
                </li>
            @endforeach
        </ul>
    </div>
</div>
