@props(['href'=>null])
<x-lareon::admin-layout>
    <x-slot:title> @yield('title') </x-slot:title>
    <x-slot:description> @yield('description') </x-slot:description>

    @section('header.start')
        @yield('header.start')
    @endsection

    @section('header.end')
        <x-lareon::search/>
    @endsection

    <div class="@container flex flex-col lg:flex-row gap-6">
        <div class="space-y-6 w-full lg:max-w-[400px]">
            @yield('form.before')
            @if(View::hasSection('form'))
                <x-lareon::box typ="y">
                    <h2 class="mb-6">
                        {{__('lareon::global.crud.titles.create_item')}}
                    </h2>
                    <form method="POST" action="{{$href}}" id="createForm">
                        @csrf
                        @yield('form')
                        <div class="mt-6">
                            <x-lareon::buttons.nav type="submit" role="submit" color="create" size="sm">
                                {{__('lareon::global.buttons.create')}}
                            </x-lareon::buttons.nav>
                        </div>
                    </form>
                </x-lareon::box>
            @endif
            @yield('form.after')
        </div>
        <div class="flex flex-col gap-6 w-full">
            @yield('list')
        </div>
    </div>
</x-lareon::admin-layout>
