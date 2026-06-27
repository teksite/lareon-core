<aside class="bg-slate-50 shadow-lg xl:shadow-none shadow-slate-600/50 fixed z-10 xl:p-1 top-0 start-0 w-3/4 md:w-1/2 lg:w-1/3 xl:w-1/9 transition-all duration-100 " :class="sidebar ? '{{is_rtl() ? 'translate-x-full' : '-translate-x-full'}} xl:translate-x-0' : 'translate-x-0 {{is_rtl() ? 'xl:translate-x-full' :'xl:-translate-x-full'}}' ">
    <div class="h-dvh relative gap-2 !p-0 xl:border-none flex flex-col justify-between">
        <figure class="p-1">
            <img src="{{auth()->user()->avatar ?? asset('assets/images/avatar-default.jpg')}}" alt="{{auth()->user()->fullname}}" width="100" height="100" fetchpriority="low" decoding="async" loading="lazy" class="mx-auto rounded-full">
            <figcaption title="{{auth()->user()->fullname}}" class="mt-1 flex gap-3 items-center justify-center font-bold text-2xl">
                    <span>
                        {{auth()->user()->name}}
                    </span>
                @if(\Illuminate\Support\Facades\Route::has('users.show'))
                    <x-lareon::links.action :href="route('users.show')"/>
                @endif
            </figcaption>
        </figure>
        <nav class="h-full p-1" id="aside-menu-nav">
            <ul class=" menu space-y-3  overflow-y-auto">
                @foreach($menus as $menu)
                    <li>
                        <x-lareon::aside.simple.nav-item :menu="$menu"/>
                    </li>
                @endforeach
            </ul>
        </nav>
        <div class="p-1">
            <button class="logoutBtn p-2 min-h-fit h-fit flex w-full items-center justify-start gap-2 text-red-600 cursor-pointer hover:bg-red-100">
                <x-icon type="outline" icon="turn-off" class="stroke-red-600" size="18"/>
                <span>
                    {{__('lareon::global.buttons.logout')}}
                </span>
            </button>
        </div>
    </div>
</aside>
