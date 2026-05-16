<aside class="fixed xl:p-1 top-0 start-0 w-3/4 md:w-1/2 lg:w-1/3 xl:w-1/6 transition-all duration-100 " :class="sidebar ? '{{is_rtl() ? 'translate-x-full' : '-translate-x-full'}} xl:translate-x-0' : 'translate-x-0 {{is_rtl() ? 'xl:translate-x-full' :'xl:-translate-x-full'}}' ">
    <div class="h-dvh relative x-box xl:border-none flex flex-col justify-between px-3">
        <div class="overflow-auto flex flex-col gap-1">
            <div class="mb-6 ">
                <div class="flex items-center gap-1">
                    <x-lareon::logo class="w-16"/>
                    <h1 class="text-3xl font-bold capitalize">
                        LAREON
                    </h1>
                </div>
                <div class="text-center text-zinc-600 font-black text-sm py-2 border-y border-line_light mt-6">
                    {{__('lareon::global.welcome_user' , ['title'=>auth()->user()->name])}}!
                </div>
            </div>
            <nav class="h-full overflow-y-auto" id="aside-menu-nav">
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
        <div class="px-3 pt-6">
            <button class="logoutBtn min-h-fit h-fit flex w-full items-center justify-between text-red-600 cursor-pointer">
                <i class='tkicon stroke-red-600 stroke-2' data-icon='turn-off'></i>
                <span>
                    {{__('lareon::global.logout')}}
                </span>
            </button>
        </div>
    </div>
</aside>
