@props([
    'optionsRoute' => 'passkey.login-options',
    'submitRoute' => 'passkey.login',
    'label' => __('sign in with a passkey'),
    'loadingLabel' => __('authenticating...'),
    'separator' => __('or continue with username and password'),
])
<div
    x-data="{ supported: false, loading: false, error: null,
        updateSupport() {
            this.supported = Boolean(window.Passkeys?.isSupported());
        },
        init() {
            this.updateSupport();

            window.addEventListener('passkeys:ready', () => this.updateSupport(), { once: true });
        },
        async verify() {
            this.loading = true;
            this.error = null;
            try {
                const response = await window.Passkeys.verify({
                    routes: {
                        options: '{{ route($optionsRoute) }}',
                        submit: '{{ route($submitRoute) }}',
                    },
                });
            } catch (e) {
                if (e.constructor?.name !== 'UserCancelledError') {
                    this.error = e.message;
                }
            } finally {
                this.loading = false;
            }
        },
    }"
>
    <template x-if="supported">
        <div>
            <div class="">
                <x-lareon::buttons.simple variant="outline" color="gray" class="mb-3 " :fullWidth="true"
                    x-on:click="verify()"
                    x-bind:disabled="loading" >
                    <div class=" flex items-center gap-2 justify-center">
                        <x-icon type="outline" icon="fingerprint" />
                        <span x-show="!loading">{{ $label }}</span>
                        <span x-show="loading" x-cloak>{{ $loadingLabel }}</span>
                    </div>
                </x-lareon::buttons.simple>
                <p x-show="error" x-text="error" x-cloak class="text-sm text-center text-red-600"></p>
            </div>

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
    </template>
</div>
