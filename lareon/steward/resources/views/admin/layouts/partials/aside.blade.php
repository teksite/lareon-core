<aside class="fixed z-20 top-0 start-0 w-16 transition-all duration-100 " :class="sidebar ? '{{is_rtl() ? 'translate-x-full' : '-translate-x-full'}} xl:translate-x-0' : 'translate-x-0 {{is_rtl() ? 'xl:translate-x-full' :'xl:-translate-x-full'}}' ">
    <div class="h-dvh relative x-box !p-0 xl:border-none flex flex-col justify-between">
        <div class="overflow-auto flex flex-col gap-1">
            <div class="">
                <div class="flex flex-col items-center gap-1 p-1">
                    <x-lareon::logo class="w-12"/>
                    <h1 class="text-sm font-bold capitalize">
                        LAREON
                    </h1>
                </div>

            </div>
            <nav class="h-full overflow-y-auto" id="aside-menu-nav">
                <ul class="menu">
                    @foreach($menus as $menu)
                        <li>
                            <x-lareon::aside.nav :menu="$menu"/>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
        <div class="p-3">
            <button title="{{__('logout')}}" class="logoutBtn mx-auto p-2 flex items-center justify-start text-red-600 cursor-pointer hover:bg-red-100 rounded-xl">
                <x-tkicon type="outline" icon="turn-off" class="stroke-red-600 mx-auto " size="18"/>
            </button>
        </div>
    </div>
</aside>
