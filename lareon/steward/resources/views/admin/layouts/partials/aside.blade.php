<aside class="fixed top-0 start-0 w-3/4 md:w-1/2 lg:w-1/3 xl:w-1/6 transition-all duration-100 " :class="sidebar ? '{{is_rtl() ? 'translate-x-full' : '-translate-x-full'}} xl:translate-x-0' : 'translate-x-0 {{is_rtl() ? 'xl:translate-x-full' :'xl:-translate-x-full'}}' ">
    <div class="h-svh relative bg-slate-50 xl:bg-transparent shadow-sm xl:shadow-none border border-zinc-200 xl:border-none flex flex-col justify-between  py-3 rounded-lg">
        <button class="font-bold text-red-700 absolute top-1 end-1 p-3" title="{{__('close')}}" type="button" role="button" @click="togglesSidebar()">X</button>
        <div class=" overflow-auto flex flex-col gap-1">
            <div class="mb-6 flex items-center gap-1 min-h-fit h-fit">
                <div class="w-16">
                    <x-lareon::logo/>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1 capitalize">
                        LAREON
                    </h1>
                    <span class="text-zinc-600 font-black text-sm">
                        {{__('welcome :title' ,['title'=>auth()->user()->name])}}!
                     </span>
                </div>
            </div>
            <nav class=" h-full overflow-y-auto" id="aside-menu-nav">
                <ul class="px-3 menu space-y-6">
                    @foreach(\steward\CMS\App\Services\MenuService::getAdminMenu() as $menu)
                        @can($menu['can']?? null)
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
                        @endcan
                    @endforeach
                </ul>
            </nav>
        </div>
        <div class="px-3 pt-6 min-h-fit h-fit">
            <button class="logoutBtn flex w-full items-center justify-between min-h-fit text-red-600 cursor-pointer">
                <i class='tkicon stroke-red-600 stroke-2' data-icon='turn-off'></i>
                <span>
                    {{__('logout')}}
                </span>
            </button>
        </div>
    </div>
</aside>
