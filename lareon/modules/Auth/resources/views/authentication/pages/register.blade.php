<x-auth::layout :title="trans('lareon::global.auth.sign_up')" :indexable="true">
    <div class="w-full">
        <div class="text-center">
            <x-icon type="outline" icon="user" size="32" class="mx-auto mb-3"></x-icon>
            <h1 class="text-center !mb-0 text-xl">{{__('lareon::global.auth.register')}}</h1>
        </div>
        <hr class="my-6 border-zinc-300">
        <form method="POST" action="{{ route('register') }}" class="formAction space-y-3">
            @csrf
            <div class="mb-6 space-y-3">
                <div class="grid gap-6 md:grid-cols-2">
                    <x-lareon::editor.input :label="__('name')" name="name" autocomplete="name" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('name')])" :required="true"/>
                    <x-lareon::editor.input :label="__('lastname')" name="lastname" autocomplete="lastname" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('lastname')])" :required="true"/>
                </div>
                <div class=" space-y-3">
                    <x-lareon::editor.input :label="__('email')" name="email" autocomplete="email" :placeholder="__('lareon::global.placeholders.write.unique.one',['attribute'=>__('email')])" :required="true"/>
                    <x-lareon::editor.input :label="__('phone')" name="phone" autocomplete="phone" :placeholder="__('lareon::global.placeholders.write.unique.one',['attribute'=>__('phone')])" :required="true"/>
                </div>
                <div>
                    <x-lareon::editor.password wrapperClass="grid gap-6 md:grid-cols-2" name="password" :label="__('password')" :placeholder="__('lareon::global.placeholders.auth.password')" :confirmLabel="__('lareon::global.placeholders.auth.password')" :required="true"/>

                </div>
            </div>
            <div class="mb-3">
                {{--                <x-captcha::load />--}}
            </div>
            <div class="">
                <x-lareon::buttons.simple type="submit" role="submit" :fullWidth="true">
                    {{__('lareon::global.buttons.register')}}
                </x-lareon::buttons.simple>
            </div>

            @if (Route::has('login'))
                <a href="{{route('register')}}" class="px-3 py-2 bg-blue-300/25 text-blue-600 block text-center mt-12 rounded-2xl font-bold">
                    {{__('lareon::global.auth.sign_in')}}
                </a>
            @endif
        </form>
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
