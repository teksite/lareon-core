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
                    @foreach($menus as $menu)
                        <li>
                            <a href="{{ $menu['url'] }}" class="{{ request()->is($menu['active_pattern'] ?? '') ? 'active' : '' }}">
                                @if($menu['icon'] ?? false)
                                    <i class="{{ $menu['icon'] }}"></i>
                                @endif
                                <span>{{ $menu['title'] }}</span>
                                @if($menu['badge'] ?? false)
                                    <span class="badge">{{ $menu['badge'] }}</span>
                                @endif
                            </a>
                        </li>
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
