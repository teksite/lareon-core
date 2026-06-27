@props([
    'optionsRoute' => 'passkey.login-options',
    'submitRoute' => 'passkey.login',
    'redirectTo' => url()->previous(),
    'label' => __('sign in with a passkey'),
    'loadingLabel' => __('authenticating...'),
    'separator' => __('or continue with username and password'),
])

<div id="passkey-login" data-options-url="{{ route($optionsRoute) }}" data-submit-url="{{ route($submitRoute) }}" data-redirect-url="{{ $redirectTo }}">

    <div id="passkey-login-wrapper" class="hidden">
        <x-lareon::buttons.simple id="passkey-login-button" variant="outline" color="gray" class="mb-3" :fullWidth="true">
            <div class="flex items-center gap-2 justify-center">
                <x-icon type="outline" icon="fingerprint"/>
                <span id="passkey-login-label">
                    {{ $label }}
                </span>

            </div>
        </x-lareon::buttons.simple>

        <p id="passkey-login-error" class="hidden text-sm text-center text-red-600"></p>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-line_light"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="px-2 text-zinc-600 bg-slate-50">
                    {{ $separator }}
                </span>
            </div>
        </div>
    </div>

</div>
