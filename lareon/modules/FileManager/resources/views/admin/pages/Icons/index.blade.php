<x-lareon::admin-layout>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('icons')]))
    @section('description', __('browse the list of available icons to use in your application'))

    <section class="mt-12">
        @foreach($groups as $type=>$icons)
            @continue(!count($icons))
            <h2 class="text-center mb-5">{{$type}}</h2>
            <ul class="grid gap-6 grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 xl:grid-cols-7">
                @foreach($icons as $name)
                    <li>
                        <x-lareon::box class="flex items-center justify-between gap-6  text-gray-600 hover:text-cyan-600">
                            <x-tkicon :icon="$name" class="fill-none stroke-current "/>
                            <span class="text-xs block text-center font-bold">{!! $name !!}</span>
                        </x-lareon::box>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </section>
</x-lareon::admin-layout>
