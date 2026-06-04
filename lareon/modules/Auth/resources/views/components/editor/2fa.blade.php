@props(['user'])
@if($user->two_factor_secret)
    <div class="">
        <x-lareon::editor.input-check :options="[[__('disable 2FA') , 0 ]]" name="enable_2fa"  value="null"/>

        @if(auth()->id() === $user->id)
            <hr class="bordering my-12">
            <div class="grid gap-6 md:grid-cols-2 items-center">
                <div>
                    <h3 class="">
                        {{__('QR code')}}
                    </h3>
                    <p class="p mb-3">
                        {{__('to use the QR code, please download the Google Authenticator app on your phone and scan the code below')}}   </p>
                    <a class="regular flex gap-3 mb-3"
                       href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en&gl=US">
                        <img src="{{asset('/storage/admin/google-play-icon.png')}}" alt="{{__('google play')}}" width="25" height="25"> {{__('download')}}
                    </a>

                </div>
                <div class="flex items-center justify-center">
                    {!! request()->user()->twoFactorQrCodeSvg(); !!}

                </div>
            </div>
        @endif
    </div>
@else
    <div class="">
        <p>
            {{__("two-factor authentication has not been enabled yet")}}.
        </p>
    </div>
@endif
