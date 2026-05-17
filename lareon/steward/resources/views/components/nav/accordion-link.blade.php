@isset($menu['children'])
    <li class="bg-white rounded-xl p-1" x-data="{show: @js(isset($menu['active']) && request()->routeIs($menu['active']))}">
        <button class="flex items-center gap-1 justify-between w-full {{isset($menu['active']) && request()->routeIs($menu['active']) ? 'text-indigo-800 font-bold' : ''}}" @click="show=!show">
                                        <span class="flex items-center gap-3 justify-start">
                                             <span class="bg-zinc-50 shadow-lg p-2 rounded-lg">
                                                  <i class="tkicon {{isset($menu['active']) && request()->routeIs($menu['active']) ? 'stroke-indigo-800 fill-none' : ''}}" data-icon="{{$menu['icon'] ?? 'paper-blank'}}"></i>
                                             </span>
                                             {{__($menu['title'])}}
                                        </span>
            <i class="tkicon justify-self-end {{isset($menu['active']) && request()->routeIs($menu['active']) ? '-rotate-90' : ''}}" data-icon="{{is_rtl() ? 'angle-left': 'angle-right' }}" size="8" :class="{'-rotate-90' : show}"></i>
        </button>
        <ul class="" x-show="show" x-collapse x-cloak>
            @foreach($menu['children'] as $item)
                @can($item['can'] ?? null)
                    <li class="my-3">
                        <a href="{{route($item['route'])}}" class="flex items-center gap-1 justify-start text-sm {{(isset($item['active']) && request()->routeIs($item['active'])) || request()->routeIs($item['route'])? 'text-indigo-800 font-bold' : ''}}">
                            <i class="tkicon {{(isset($item['active']) && request()->routeIs($item['active'])) || request()->routeIs($item['route'])? 'stroke-indigo-800 fill-none' : ''}}" data-icon="circle" size="8"></i>
                            {{__($item['title'])}}
                        </a>
                    </li>
                @endcan
            @endforeach
        </ul>
    </li>
@else
    <li class="bg-white rounded-xl p-1">
        <a href="{{route($menu['route'])}}" class="flex items-center gap-1 justify-start {{(isset($menu['active']) && request()->routeIs($menu['active'])) || request()->routeIs($menu['route'])? 'text-indigo-800 font-bold' : ''}}">
                                   <span class="bg-zinc-50 shadow-lg p-2 rounded-lg">
                                       <i class="tkicon {{(isset($menu['active']) && request()->routeIs($menu['active'])) || request()->routeIs($menu['route'])? 'stroke-indigo-800 fill-none' : ''}}" data-icon="{{$menu['icon'] ?? 'paper-blank'}}"></i>
                                   </span>
            {{__($menu['title'])}}
        </a>
    </li>
@endisset
