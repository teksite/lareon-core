<x-auth::layout :title="trans('lareon::global.auth.sign_in')">
    <section class="w-11/12 md:2-3/4 mx-auto my-3">

        <div class="text-center">
            <x-icon type="outline" icon="user" size="32" class="mx-auto mb-3"></x-icon>
            <h1 class="text-center !mb-0 text-xl">{{__('lareon::global.auth.login')}}</h1>
        </div>
        <p>
            {{ __('to enable two factor authentication please enter your password in the below field') }}
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="mb-3">
                <x-lareon::inputs.label for="password" :value="__('password')"/>

            </div>

            <div class="flex justify-end">
                <x-lareon::buttons.nav>
                    {{ __('confirm') }}
                </x-lareon::buttons.nav>
            </div>
        </form>
    </section>
</x-auth::layout>
