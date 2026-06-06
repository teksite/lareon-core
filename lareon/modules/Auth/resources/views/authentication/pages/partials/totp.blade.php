<div x-show="activeTab === 1">
    <section>
        <form method="POST" action="{{route('auth.two-factor.login.store')}}" class="formAction">
            @csrf
            <div class="text-center mb-6 ">
                <x-lareon::inputs.label for="code" :title="__('enter the code from your authentication app')" class=""/>
                <div class="flex items-center justify-center gap-1 mt-3" dir="ltr">
                    @for($i=0 ;$i<6 ; $i++)
                        <input class="totpField oneFieldInput border border-zinc-300 px-4 py-3 w-12 h-12 font-bold {{$i==2 ? 'me-3' : ''}} {{$i==3 ? 'ms-3': ''}}" name="code[]" type="text" maxlength=1>
                    @endfor
                </div>
                <x-lareon::inputs.error :messages="$errors->get('code')" class="mt-2"/>
            </div>
            <div class="">
                <x-lareon::buttons.simple id="totpFieldSubmit" type="submit" rol="button" :fullWidth="true" title="{{ __('confirm') }}">
                    {{ __('confirm') }}
                </x-lareon::buttons.simple>
            </div>
        </form>
    </section>
</div>
