<x-auth::layout :title="trans('lareon::global.auth.email_verification')">
    <div class="w-full">
        <div class="text-center">
            <x-icon type="outline" icon="email" size="32" class="mx-auto mb-3"></x-icon>
            <h1 class="text-center !mb-0 text-xl">{{__('lareon::global.auth.email_verification')}}</h1>
        </div>
        <hr class="my-6 border-zinc-300">
        <div class="mb-6 space-y-3">
            <p class=" text-center leading-9 font-semibold">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you did not receive the email, we will gladly send you another') }}.
            </p>
        </div>
        <div class="">
            <form method="POST" action="{{ route('verification.send') }}" class="formAction">
                @csrf
                <x-lareon::buttons.simple type="submit" role="submit" :fullWidth="true">
                    {{__('lareon::global.buttons.resend_email')}}
                </x-lareon::buttons.simple>
            </form>
        </div>
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
