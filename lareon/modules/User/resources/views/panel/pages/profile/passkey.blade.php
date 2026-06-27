<x-lareon::panel-layout>
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('profile')]) . "($user->fullname)")
    @section('nav' ,view('user::panel.pages.profile.partials.nav'))

    <x-auth::editor.passkeys :passkeys="$passkeys"/>
    <div id="passkey-manager" data-option-url="{{ route('passkey.registration-options') }}" data-store-url="{{ route('passkey.store') }}">
        <x-lareon::buttons.simple variant="solid" size="sm" color="create" x-on:click="$dispatch('open-modal', 'addPasskey')">
            {{ __('add') }}
        </x-lareon::buttons.simple>

        <x-lareon::modal name="addPasskey">
            <form id="passkey-form">
                @csrf
                <x-lareon::editor.input label="{{ __('passkey name') }}" placeholder="{{ __('e.g., MacBook Pro, iPhone') }}" name="name" id="passkey-name"/>
                <p class="text-xs text-gray-600 mt-1">
                    {{ __('give this passkey a name to help you identify it later') }}
                </p>
                <p id="passkey-error" class="text-sm text-red-600 mt-2 hidden"></p>
                <div class="flex gap-3 mt-6">
                    <x-lareon::buttons.simple type="submit" role="submit" size="xs" variant="solid" class="w-24" id="register-passkey">
                        {{ __('register') }}
                    </x-lareon::buttons.simple>

                    <x-lareon::buttons.simple type="button" size="xs" variant="outline" class="w-24" id="cancel-passkey">
                        {{ __('cancel') }}
                    </x-lareon::buttons.simple>
                </div>
            </form>
        </x-lareon::modal>

    </div>

</x-lareon::panel-layout>
