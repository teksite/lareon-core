<x-auth::layout :title="trans('lareon::global.auth.sign_up')" :indexable="true">
    <div class="w-full">
        <div class="text-center">
            <x-tkicon type="outline" icon="user" size="32" class="mx-auto mb-3" />
            <h1 class="text-center !mb-0 text-xl">{{__('lareon::global.auth.register')}}</h1>
        </div>
        <hr class="my-6 border-zinc-300">
        <form method="POST" action="{{ route('register') }}" class="formAction space-y-6">
            @csrf
            <div class="mb-6 space-y-6">
                    <x-lareon::editor.input :label="__('name')" name="name" autocomplete="name" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('name')])" :required="true"/>
                <div class=" space-y-6">
                    <x-lareon::editor.input :label="__('email')" name="email" autocomplete="email" :placeholder="__('lareon::global.placeholders.write.unique.one',['attribute'=>__('email')])" :required="true"/>
                    <x-lareon::editor.input :label="__('phone')" name="phone" autocomplete="phone" :placeholder="__('lareon::global.placeholders.write.unique.one',['attribute'=>__('phone')])" :required="true"/>
                </div>
                <div>
                    <x-lareon::editor.input-password wrapperClass="grid gap-6 md:grid-cols-2" name="password" :label="__('password')" :placeholder="__('lareon::global.placeholders.auth.password')" :confirmLabel="__('lareon::global.placeholders.auth.password')" :required="true"/>

                </div>
            </div>
            <div>
                @captchaField()
                <x-lareon::inputs.error :messages="$errors->first('captcha') ?? null"/>
            </div>

            <div class="">
                <x-lareon::buttons.simple type="submit" role="submit" :fullWidth="true">
                    {{__('lareon::global.buttons.register')}}
                </x-lareon::buttons.simple>
            </div>

            @if (Route::has('login'))
                <div class="w-full text-center">
                    <a href="{{route('login')}}" class="px-3 py-2 text-blue-600 block text-center rounded-2xl font-bold text-sm">
                        {{__('lareon::global.auth.sign_in')}}
                    </a>
                </div>
            @endif
        </form>
        <div class="mt-6">
            <div class="flex items-center gap-3">
                <hr class="border-slate-300 w-full">
                <span class="w-fit min-w-fit text-gray-600 font-bold text-sm">
                   {{__('or')}} {{__('via')}}
                </span>
                <hr class="border-slate-300 w-full">
            </div>
            <div class="flex items-center gap-3 justify-center mt-3">
                <x-auth::oauth-ways/>
            </div>
        </div>
        @section('footer')
            <section class="">
                <a href="/" class="text-sm inline-flex items-center gap-1">
                    <x-tkicon icon="home" type="outline" size="20" />
                    {{__('lareon::global.links.back_home')}}
                </a>
            </section>
        @endsection
    </div>

</x-auth::layout>
