<x-auth::layout :title="trans('lareon::global.auth.sign_in')">
    <section class="w-11/12 md:2-3/4 mx-auto my-3">
        <p>
            {{ __('to enable two factor authentication please enter your password in the below field') }}
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="mb-3">
                <x-lareon::inputs.label for="password" :value="__('password')"/>
                <x-lareon::inputs.text id="password" class="block w-full" type="password" name="password" required
                              autocomplete="current-password"/>
                <x-lareon::inputs.error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <div class="flex justify-end">
                <x-lareon::buttons.nav>
                    {{ __('confirm') }}
                </x-lareon::buttons.nav>
            </div>
        </form>
    </section>
</x-auth::layout>
