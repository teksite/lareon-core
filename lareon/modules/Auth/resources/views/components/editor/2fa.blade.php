@props(['user' , 'enabling'=>false])
@if($user->two_factor_secret)
    <div class="">
        <x-lareon::editor.input-check :options="[[__('disable 2FA') , 0 ]]" name="enable_2fa" value="null"/>
        @if(auth()->id() === $user->id)
            <hr class="bordering my-12">
            <div class="grid gap-6 md:grid-cols-2 items-center">
                <div>
                    <h3 class="">
                        {{__('QR code')}}
                    </h3>
                    <p class="p mb-3">
                        {{__('To use the QR code, please download the Google Authenticator app on your phone and scan the code below')}}
                    </p>
                    <a class="regular flex gap-3 mb-3"
                       href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en&gl=US">
                        <x-icon type="outline" icon="googleplay"/>
                        {{__('download')}}
                    </a>

                </div>
                <div class="flex items-center justify-center">
                    {!! request()->user()->twoFactorQrCodeSvg(); !!}
                </div>
            </div>
            <hr class="bordering my-12">

            <div class="grid gap-6 md:grid-cols-2 items-center">
                <div class="space-y-6">
                    <h3 class="">
                        {{__('recovery code')}}
                    </h3>
                    <p class="p">
                        {{__("If you don't have access to your authenticator app, use these codes")}}
                    </p>
                    <div class="warning-msg flex items-center gap-2 justify-start">
                        <div class="relative inline-flex size-3">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-yellow-600 opacity-75"></span>
                            <span class="relative inline-flex size-3 rounded-full bg-yellow-600"></span>
                        </div>
                        <p>
                            {{__('caution')}}: {{__("Save these codes in a safe place and don't share them")}}
                        </p>
                    </div>
                </div>
                <ul class="space-y-3">
                    @foreach($user->recoveryCodes() as $recovery)
                        <li class="text-start" dir="ltr">{{$recovery}}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
@else
    <div class="">
        <p class="mb-6">
            {{__("two-factor authentication has not been enabled yet")}}.
        </p>
        @if($enabling)
            <x-lareon::editor.input-check :options="[[__('enable 2FA') , 0 ]]" name="enable_2fa" value="1"/>
        @endif
    </div>
@endif
