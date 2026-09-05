<x-lareon::admin-layout>
    @section('title', __('robot.txt'))
    @section('description', __("manage and edit the robots.txt file to control how search engine crawlers access and index your website’s pages and resources"))

    <section class="mb-6" x-data="{ dark: true, wrap: true }">
            <div class="flex items-center justify-end my-3 mt-12">
                <button type="button" @click="dark = !dark" class="px-3 py-1 rounded border text-sm">
                    <span x-text="dark ? '☀️ Light' : '🌙 Dark'"></span>
                </button>
            </div>
        <form action="{{route('admin.seo.robot.update')}}" method="POST">
            @csrf
            @method('PATCH')
            <textarea name="content" dir="ltr" class="input font-semibold w-full max-h-screen overflow-auto transition-colors duration-300" :class="dark ? 'bg-zinc-900 text-gray-300': 'bg-slate-50 text-gray-900'" rows="24">{!! $content !!}</textarea>

            <div class="mt-6">
                <x-lareon::buttons.nav class="w-24" :fullWidth="false" type="submit" role="submit" color="create">
                    {{ __('update')}}
                </x-lareon::buttons.nav>
            </div>
        </form>
    </section>

</x-lareon::admin-layout>
