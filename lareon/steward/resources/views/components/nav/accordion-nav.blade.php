@props(['menu'])
@php
    $href=isset($menu['route']) ? route($menu['route']) : ($menup['url'] ?? null);
@endphp

@if(empty($menu['children']))
    <div class="rounded-e-xl p-2">
        <div class="flex items-center justify-start gap-2 w-full">
            <x-icon type="{{$menu['icon-type'] ??  'outline' }}" icon="{{$menu['icon'] ??  'circle'}}" class="w-6 fill-none stroke-gray-600"/>
            <a href="{{$href}}" class="flex items-center gap-1 justify-start {{(isset($menu['active']) && request()->routeIs($menu['active'])) ? 'text-indigo-800 font-bold' : ''}}">
               <span>
                    {{__($menu['title'])}}
               </span>
            </a>
        </div>
    </div>
@else
    <div class="rounded-e-xl p-2" x-data="{show: false}">
        <button type="button" class="flex items-center justify-start gap-2 w-full"  @click="show=!show">
            <x-icon type="{{$menu['icon-type'] ??  'outline' }}" icon="{{$menu['icon'] ??  'circle'}}" class="w-6 fill-none stroke-gray-600"/>
            <span>
                {{__($menu['title'])}}
            </span>
            <x-icon type="outline" icon="angle-right" class="w-3 fill-none stroke-gray-600 ms-auto me-0 "/>

        </button>
        <ul x-show="show" x-collapse x-cloak class="*:flex *:items-center *:justify-start *:gap-2 space-y-1 p-2">
            @if($href)
                <li>
                    <x-icon type="{{$menu['icon-type'] ??  'outline' }}" icon="{{$menu['icon'] ??  'circle'}}" class="w-2 fill-none stroke-gray-600"/>
                    <a href="{{$href}}" class="{{(isset($menu['active']) && request()->routeIs($menu['active'])) ? 'text-indigo-800 font-bold' : ''}}">
                        {{__($menu['title'])}}
                    </a>
                </li>
            @endif
            @foreach($menu['children'] as $child)
                @php
                    $hrefChild=isset($child['route']) ? route($child['route']) : ($child['url'] ?? null);
                @endphp
                <li>
                    <x-icon type="{{$child['icon-type'] ??  'outline' }}" icon="{{$child['icon'] ??  'circle'}}" class="w-2 fill-none stroke-gray-600"/>
                    <a href="{{$hrefChild}}" class="flex items-center gap-1 justify-start {{(isset($child['active']) && request()->routeIs($child['active'])) ? 'text-indigo-800 font-bold' : ''}}">
                        {{$child['title']}}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif




