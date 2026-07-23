<x-auth::layout :title="trans('lareon::global.auth.sign_in')" layput="center">
    <section class="w-11/12 md:2-3/4 mx-auto space-y-6">
        <div class="text-center">
            <x-tkicon type="outline" icon="password" size="32" class="mx-auto mb-3"></x-icon>
            <h1 class="text-center !mb-0 text-xl">{{__('lareon::global.auth.confirm_password')}}</h1>
        </div>
        <p class="text-center">
            {{ __('to continue the process, you should enter your password') }}.
        </p>
        <form method="POST" action="{{ route('password.confirm') }}" >
            @csrf
            <x-lareon::editor.password :label="__('password')" name="password" :placeholder="__('lareon::global.placeholders.auth.password',['attribute'=>__('password')])" :showConfirm="false" :strength="false"/>
            <div class="flex justify-end mt-3">
                <x-lareon::buttons.nav>
                    {{ __('lareon::global.buttons.confirm') }}
                </x-lareon::buttons.nav>
            </div>
        </form>
    </section>
</x-auth::layout>
