<div x-show="activeTab === 2" x-transition.opacity.duration.300ms>
    <section>
        <form method="POST" action="{{route('auth.recovery.login.store')}}" class="formAction">
            @csrf
            <div class="text-center mb-6 ">
                <x-lareon::inputs.label for="code" :title="__('enter the code from your authentication app')" class=""/>
                <div class="flex items-center justify-center gap-1 mt-3" dir="ltr">
                        <input class="border border-zinc-300 px-4 py-3 font-bold" name="recovery_code" type="text" >
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
