@props(['menu'])
@php
    $route = $menu['route'] ?? null;
    $url = $menu['url'] ?? null ;
    $preActivation = isset($menu['active']) && $menu['active'];
    $href=$route ? route($route) : $url;

    $active= $preActivation
     ||($route && request()->routeIs($route)
     || ($url) && str_starts_with($url , request()->url()));

    foreach ($menu['children'] ?? [] as $child){
       if ( isset($child['active'])&& $child['active']) {
           $active=true;
           break;
       }
    }
@endphp
<div class="rounded-e-xl p-2" x-data="{show:{{$active ? 'true' : 'false'}}}">
    <button type="button" class="flex items-center justify-start gap-2 w-full outline-none" @click="show=!show">
       <span>
            <x-tkicon type="{{$menu['icon-type'] ?? 'outline' }}" icon="{{$menu['icon'] ??  'circle'}}" high="18" width="18" :class="$active ? 'fill-none stroke-indigo-800' : 'fill-none stroke-gray-600'"/>
       </span>
        <span class="{{$active ? 'text-indigo-800 font-bold' : ''}}">
            {{__($menu['title'] ?? '')}}
        </span>
        <x-tkicon type="outline" icon="{{is_rtl() ? 'angle-left' :'angle-right'}}" size="10" class="w-3 fill-none stroke-gray-600 ms-auto me-0 " x-bind:class="show ? 'rotate-90' :''"/>
    </button>
    <ul x-show="show" x-collapse x-cloak class="*:flex *:items-center *:justify-start *:gap-2 space-y-1 p-2">
        @if($href)
           <li>
               <x-lareon::accordion-nav.link :menu="$menu" size="28"/>
           </li>
        @endif
        @foreach($menu['children'] ?? [] as $child)
            <li>
                <x-lareon::accordion-nav.link :menu="$child" size="10"/>
            </li>
        @endforeach
    </ul>

</div>
