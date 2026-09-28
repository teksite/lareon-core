<x-auth::layout :title="trans('lareon::global.auth.sign_in')" :indexable="true">
    @section('title',__('login'))
    <div class="w-full">
        <div class="text-center">
            <x-tkicon type="outline" icon="user" size="32" class="mx-auto mb-3"/>
            <h1 class="text-center !mb-0 text-xl">{{__('lareon::global.auth.login')}}</h1>
        </div>
        <hr class="my-6 border-zinc-300">

        <x-auth::passkey/>

        <form method="POST" action="{{ route('login.store') }}" class="formAction space-y-3">
            @csrf
            <div class="mb-6 space-y-3">
                <x-lareon::editor.input :label="__('username')" name="username" autocomplete="email" :placeholder="__('lareon::global.placeholders.auth.username')" :required="true"/>
                <div class="">
                    <div class="flex items-center justify-between gap-3 mb-1">
                        <x-lareon::inputs.label :title="__('password')" for="password" :markAsRequire="true"/>
                        @if (Route::has('password.request'))
                            <a href="{{route('password.request')}}" class="text-xs text-red-900 text-end font-semibold">
                                {{__('lareon::global.links.forget_password')}}
                            </a>
                        @endif
                    </div>
                    <x-lareon::editor.input-password name="password" :strength="false" :showConfirm="false" :placeholder="__('lareon::global.placeholders.auth.password')" :required="true"/>
                </div>
            </div>

            <div class="flex justify-start items-center gap-2">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('remember me') }}</span>
                </label>

            </div>
            <div class="mb-3">
                {{--                <x-captcha::load />--}}
            </div>
                <x-lareon::buttons.simple type="submit" role="submit" :fullWidth="true">
                    {{__('lareon::global.buttons.sign_in')}}
                </x-lareon::buttons.simple>
            @if (Route::has('register'))
                <div class="w-full text-center">
                    <a href="{{route('register')}}" class="px-3 py-2 text-blue-600 block text-center rounded-2xl font-bold text-sm">
                        {{__("i don't account yet")}}!
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
                    <x-tkicon icon="home" type="outline" size="20"/>
                    {{__('lareon::global.links.back_home')}}
                </a>
            </section>
        @endsection
    </div>

</x-auth::layout>
