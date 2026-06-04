<x-auth::layout :title="trans('lareon::global.auth.sign_in')">
@section('title',__('login'))
    <div class="w-full">
        <div class="text-center">
            <x-icon type="outline" icon="user" size="32" class="mx-auto mb-3"></x-icon>
            <h1 class="text-center !mb-0 text-xl">{{__('lareon::global.auth.login')}}</h1>
        </div>
        <hr class="my-6 border-zinc-300">
        <form method="POST" action="{{ route('login') }}" class="formAction space-y-3">
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
                    <x-lareon::editor.password name="password" :strength="false" :showConfirm="false" :placeholder="__('lareon::global.placeholders.auth.password')" :required="true"/>
                </div>
            </div>

            <div class="flex justify-start items-center gap-2">
                <x-lareon::inputs.checkbox id="remember" name="remember"/>
                <x-lareon::inputs.label for="remember" :title="__('lareon::global.placeholders.auth.remember')" class="!text-sm"/>
                <x-lareon::inputs.error :messages="$errors->first('remember') ?? null"/>

            </div>
            <div class="mb-3">
                {{--                <x-captcha::load />--}}
            </div>
            <div class="">
                <x-lareon::buttons.simple type="submit" role="submit" :fullWidth="true">
                    {{__('lareon::global.buttons.sign_in')}}
                </x-lareon::buttons.simple>
            </div>
        </form>
            @if (Route::has('register'))
                <a href="{{route('register')}}" class="px-3 py-2 bg-blue-300/25 text-blue-600 block text-center mt-12 rounded-2xl font-bold">
                    {{__('lareon::global.auth.sign_up')}}
                </a>
            @endif

        @section('footer')
            <section class="">
                <a href="/" class="text-sm inline-flex items-center gap-1">
                    <x-icon icon="home" type="outline" size="20"></x-icon>
                    {{__('lareon::global.links.back_home')}}
                </a>
            </section>
        @endsection
    </div>

</x-auth::layout>
