<aside class="fixed xl:p-1 top-0 start-0 w-3/4 md:w-1/2 lg:w-1/3 xl:w-1/6 transition-all duration-100 " :class="sidebar ? '{{is_rtl() ? 'translate-x-full' : '-translate-x-full'}} xl:translate-x-0' : 'translate-x-0 {{is_rtl() ? 'xl:translate-x-full' :'xl:-translate-x-full'}}' ">
    <div class="h-dvh relative x-box !p-0 xl:border-none flex flex-col justify-between">
        <div class="overflow-auto flex flex-col gap-1">
            <div class="mb-6 ">
                <div class="flex items-center gap-1">
                    <x-lareon::logo class="w-16"/>
                    <h1 class="text-3xl font-bold capitalize">
                        LAREON
                    </h1>
                </div>
                <div class="text-center text-zinc-600 font-black text-sm py-2 border-y border-line_light mt-6">
                    {{__('lareon::global.welcome_user' , ['attribute'=>auth()->user()->name])}}!
                </div>
            </div>
            <nav class="h-full overflow-y-auto" id="aside-menu-nav">
                <ul class="pe-3 menu space-y-6">
                    @foreach($menus as $menu)
                        <li>
                           <x-lareon::nav.accordion-nav :menu="$menu"/>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
        <div class="px-3 py-6">
            <button class="logoutBtn p-2 min-h-fit h-fit flex w-full items-center justify-start gap-2 text-red-600 cursor-pointer hover:bg-red-100">
                <x-icon type="outline" icon="turn-off" class="stroke-red-600" size="18"/>
                <span>
                    {{__('lareon::global.logout')}}
                </span>
            </button>
        </div>
    </div>
</aside>
