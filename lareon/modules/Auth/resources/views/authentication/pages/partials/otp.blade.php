<div x-show="activeTab === 3" x-transition.opacity.duration.300ms>
    <section>
        <form method="POST" action="{{route('auth.otp.login.store')}}" class="formAction" id="sendOtpGuest">
            @csrf
            <input type="hidden" name="action" value="{{\Lareon\Modules\Auth\App\Enums\VerificationActionType::LOGIN->value}}">
            <div class="text-center mb-6 ">
                <x-lareon::inputs.label for="code" :title="__('enter the code from your authentication app')" class=""/>
                <div class="flex items-center justify-center gap-1 mt-3" dir="ltr">
                    @for($i=0 ;$i<6 ; $i++)
                        <input class="otpField oneFieldInput border border-zinc-300 px-4 py-3 w-12 h-12 font-bold {{$i==2 ? 'me-3' : ''}} {{$i==3 ? 'ms-3': ''}}" name="otp_code[]" type="text" maxlength=1>
                    @endfor
                </div>
                <x-lareon::inputs.error :messages="$errors->get('otp_code')" class="mt-2"/>
            </div>
            <div class="flex items-center my-12" id="sendOtp">
                @if(true)
                    <x-lareon::buttons.simple id="sendOtpViaEmail" color="blue" variant="simple" size="xs" role="button" type="button">
                        {{__('send via email')}}
                    </x-lareon::buttons.simple>
                @endif
                @if(true)
                    <x-lareon::buttons.simple id="sendOtpViaSMS" color="blue" variant="simple" size="xs" role="button" type="button">
                        {{__('send via SMS')}}
                    </x-lareon::buttons.simple>
                @endif
            </div>
            <div class="">
                <x-lareon::buttons.simple id="totpFieldSubmit" type="submit" rol="button" :fullWidth="true" title="{{ __('confirm') }}">
                    {{ __('confirm') }}
                </x-lareon::buttons.simple>
            </div>
        </form>
    </section>
</div>
