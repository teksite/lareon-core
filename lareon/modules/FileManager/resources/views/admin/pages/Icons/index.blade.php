<x-lareon::admin-layout>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('icons')]))
    @section('description', __('browse the list of available icons to use in your application'))

    <div class="mt-12 grid lg:grid-cols-2 gap-6 overflow-x-auto">
        <div class="w-full">
            <h3>
                SSR
            </h3>
            <dev class="mb-3 bg-zinc-900 overflow-x-auto rounded p-3 text-slate-50 block" dir="ltr" style="box-shadow:-5px 0 0 0 green">
                <pre><code class="font-bold">&lt;<span>x-icon</span> class='tkicon stroke-black fill-none' size='24' icon='example' /&gt;</code></pre>

            </dev>
            <dev class="mb-3 bg-zinc-900 overflow-x-auto rounded p-3 text-slate-50 block" dir="ltr" style="box-shadow:-5px 0 0 0 green">
                <pre><code class="font-bold">&lt;<span>x-tkicon</span> class='tkicon stroke-black fill-none' size='24' icon='example' /&gt;</code></pre>
            </dev>
        </div>
        <div class="w-full">
            <h3>
                CSR
            </h3>
            <dev class="mb-3 bg-zinc-900 overflow-x-auto rounded p-3 text-slate-50 block" dir="ltr" style="box-shadow:-5px 0 0 0 green">
                <pre><code class="font-bold">&lt;<span>i</span> class='tkicon stroke-black fill-none' size='24' data-icon='example'&gt;&lt;<span>/i</span>&gt;</code></pre>

            </dev>
            <dev class="mb-3 bg-zinc-900 overflow-x-auto rounded p-3 text-slate-50 block" dir="ltr" style="box-shadow:-5px 0 0 0 blue">
        <pre><code class="font-bold">&lt;<span>style</span>&gt;
    .tkicon.example:nth-child(1) {
      fill:none;
      stroke: <span>#696969</span>
    }
    .tkicon.example:nth-child(2) {
        fill:none;
        stroke: #696969
    }
&lt;<span>style</span>&gt;</code>
</pre>
            </dev>
        </div>
    </div>
    <hr class="border-line_light my-6">
    <hr class="border-line_light my-6">
    <section class="">

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
