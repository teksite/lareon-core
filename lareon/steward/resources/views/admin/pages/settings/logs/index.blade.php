<x-lareon::admin-layout>
    @section('title', __('caches'))
    @section('description', __("manage, clear, and optimize your application's caches from a single place"))
    @section('header.start')
        <form action="" method="get" id="changeLog">
            <x-lareon::editor.input-select wrapperClass="flex gap-3 items-center" :label="__('files')" name="name" onchange="this.form.submit()" aria-label="{{__('logs')}}">
                @foreach($logs ?? [] as $file)
                    <option
                        value="{{$file}}" {{request()->input('name' ,'laravel') == $file ? 'selected' : ''}}>{{$file}}</option>
                @endforeach
            </x-lareon::editor.input-select>
        </form>
    @endsection

   {{-- @section('header.end')
        @can('admin.setting.log.clear')
            <form action="{{route('admin.settings.logs.clear')}}" method="POST">
                @csrf
                @method('delete')
                <input type="hidden" class="hidden" name="log" value="{{$name}}">
                <x-lareon::button.solid color="red">
                    {{__('clear')}}
                </x-lareon::button.solid>
            </form>
        @endcan
        @can('admin.setting.log.delete')
            <form action="{{route('admin.settings.logs.destroy')}}" method="POST">
                @csrf
                @method('delete')
                <input type="hidden" class="hidden" name="log" value="{{$name}}">
                <x-lareon::button.outline color="red">
                    {{__('delete')}}
                </x-lareon::button.outline>
            </form>

    @endsection
        @endcan--}}

    <section class="mb-6" x-data="{ dark: true, wrap: true }">
        <div class="flex items-center justify-end mb-2">
            <button type="button" @click="dark = !dark" class="px-3 py-1 rounded border text-sm">
                <span x-text="dark ? '☀️ Light' : '🌙 Dark'"></span>
            </button>
            <button type="button" @click="wrap = !wrap" class="px-3 py-1 rounded border text-sm">
                <span x-text="wrap ? '➡️ unwrap' : '↩️  wrap'"></span>
            </button>
        </div>
        <div dir="ltr" class="font-semibold p-6 rounded-lg w-full max-h-screen overflow-auto transition-colors duration-300"
             :class="dark ? 'bg-zinc-900 text-gray-300'    : 'bg-slate-50 text-gray-900 border'">
            <pre   :class="wrap ? 'whitespace-pre-wrap'    : ''">{!! $content !!}</pre>
        </div>

    </section>

</x-lareon::admin-layout>
