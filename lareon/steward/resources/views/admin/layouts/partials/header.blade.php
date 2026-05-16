<header class="mb-6 w-full rounded-lg overflow-hidden  bg-theme-4 bg-cover bg-no-repeat bg-fixed">
    <div class="bg-linear-to-r from-cyan-500 to-blue-500 50 w-full h-full ">
        <div class="w-full h-full p-3 min-h-64 flex items-center">
            <div class="px-3">
                <h2 class="mb-3 text-zinc-50">
                    @yield('title' , __('dashboard'))
                </h2>
                <p class="font-semibold text-zinc-300">
                    @yield('description' , '')
                </p>
            </div>
        </div>
        @if(View::hasSection('header.start') || View::hasSection('header.end') )
            <div class="x-box flex flex-col sm:flex-row items-center justify-between gap-6 -translate-y-12 -mt-12 w-11/12 mx-auto">
                <div class="flex items-center justify-start gap-3">
                    @yield('header.start')
                </div>
                <div class="flex items-center justify-end gap-3">
                    @yield('header.end')
                </div>
            </div>
        @endif
    </div>
</header>
