<x-lareon::admin-layout>
    @section('title', __('maintenance mode'))
    @section('description', __("on this page, you can enable or disable Maintenance Mode for the app"))

    <section class="w-full mx-auto lg:w-1/2">
        <div>
            <div class="flex items-center justify-start gap-3 bordering p-3 rounded-lg my-6 {{$isDown ? 'bg-red-100' :'bg-green-100'}}">
                <div class="">
                    <span class="relative flex size-3">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full {{$isDown ? 'bg-red-400': 'bg-green-600'}} opacity-75"></span>
                        <span class="relative inline-flex size-3 rounded-full {{$isDown ? 'bg-red-500': 'bg-green-600'}}"></span>
                    </span>
                </div>
                <div class="">
                    <p class="font-bold select-none {{$isDown ? 'text-red-600' : 'text-green-600'}}">
                        {{__($isDown ? 'the app is down' : 'the app is up')}}
                    </p>
                </div>
            </div>
            <x-lareon::box type="y">
                <form action="{{route('admin.settings.maintenance.update')}}" method="post">
                    @csrf
                    @method('PATCH')
                    <p class="mb-6">
                        {{__("if you enter a secret code, the application will be put into maintenance mode (and any new code will replace the previous one); if you leave it empty, the application will return to normal mode")}}.
                    </p>
                    <p class="mb-6">
                        {{__("you can access the app using this address even when it is in maintenance mode")}}.
                    </p>
                    <p dir="ltr">
                        {{url('{secrete-code}')}}
                    </p>

                        <div class="flex items-end">
                            <x-lareon::editor.input :value="old('secret')" :label="__('secret code')" name="secret" :placeholder="__('leave it empty to be disabled or enter a secret code')" :required="false"/>
                            <x-lareon::buttons.nav rounded="md" size="xs" class="min-w-fit w-fit" color="update" type="submit" :fullWidth="false">
                                {{__('update')}}
                            </x-lareon::buttons.nav>
                        </div>
                </form>
            </x-lareon::box>
        </div>
    </section>

</x-lareon::admin-layout>
