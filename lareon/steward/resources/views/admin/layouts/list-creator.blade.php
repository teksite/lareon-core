@props(['href'=>null])
<x-lareon::admin-layout>
    <x-slot:title> @yield('title') </x-slot:title>
    <x-slot:description> @yield('description') </x-slot:description>

    <x-slot:header.start> @yield('header.start') </x-slot:header.start>

    <x-slot:header.end> @yield('header.end') </x-slot:header.end>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="space-y-6">
            @yield('form.before')
            @if(View::hasSection('form'))
                <x-lareon::box typ="y">
                    <h2 class="mb-6">
                        {{__('lareon::global.crud.titles.create_item')}}
                    </h2>
                    <form method="POST" action="{{$href}}" id="createForm">
                        @csrf
                        @yield('form')
                        <div class="flex items-center justify-end">
                            <x-lareon::buttons.nav type="submit" role="submit" color="create">
                                {{__('lareon::global.crud.buttons.create')}}
                            </x-lareon::buttons.nav>
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
