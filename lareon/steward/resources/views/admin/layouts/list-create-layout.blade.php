<x-lareon::admin-layout>
    @section('title')
        @yield('title')
    @endsection
    @section('header-description')
        @yield('header-description')
    @endsection

    @section('hero.start')
        @yield('hero.start')
    @endsection

    @section('hero.end')
        @yield('hero.end')
    @endsection

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="">
            @if(View::hasSection('form'))
                <x-lareon::box>
                    <h2 class="mb-6">
                        {{__('create a new item')}}
                    </h2>
                    <form method="POST" action="@yield('formRoute')" id="createForm">
                        @csrf
                        @yield('form')
                        <div class="flex items-center justify-end">
                            <x-lareon::button.solid type="submit" role="submit" color="green">
                                {{__('add')}}
                            </x-lareon::button.solid>
                        </div>
                    </form>
                </x-lareon::box>
            @endif
            @yield('form.after')
        </div>

        <div class="lg:col-span-2 flex flex-col gap-6">
            @yield('list')
        </div>
    </div>
</x-lareon::admin-layout>
