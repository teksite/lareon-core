<x-lareon::admin-layout>
    @section('title', __('sitemap'))
    @section('description', __("create and manage your website’s XML sitemap to help search engines discover, crawl, and index your website pages more efficiently"))

    <section class="w-full mx-auto lg:w-1/2 mt-12">
        <x-lareon::box type="y">
            <form method="post" action="{{route('admin.seo.sitemaps.generate')}}">
                @csrf
                @method('PATCH')
                <p class="mb-6">
                    {{__("click the Update button to regenerate the sitemap file(s)")}}.
                </p>
                <div class="flex gap-3 items-center">
                    <span class="relative flex size-3">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-cyan-600 opacity-75"></span>
                        <span class="relative inline-flex size-3 rounded-full bg-cyan-600"></span>
                    </span>
                    {{__('for automatic and continuous updates, it is recommended to configure a Cron Job, Scheduler, or Job to regularly regenerate and update the sitemap files')}}.
                </div>
                <div class="flex justify-between items-end mt-12">
                    <p class="text-gray-600">
                        {{__('sitemap generation type')}}:
                        <span class="text-gray-900 text-lg font-black">{{config('seo.sitemap.type' ,'index')}}</span>
                    </p>
                    <x-lareon::buttons.nav rounded="md" size="xs" class="min-w-fit w-fit" color="update" type="submit" :fullWidth="false">
                        {{__('update')}}
                    </x-lareon::buttons.nav>
                </div>
            </form>
        </x-lareon::box>

    </section>

</x-lareon::admin-layout>

