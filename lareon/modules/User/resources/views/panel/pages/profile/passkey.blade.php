<x-lareon::panel-layout>
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('profile')]) . "($user->fullname)")
    @section('nav' ,view('user::panel.pages.profile.partials.nav'))


    <x-auth::editor.passkeys :passkeys="$passkeys"/>


    <div data-option-url="{{route('passkey.registration-options')}}" data-store-url="{{route('passkey.store')}}">

        {{-- ADD BUTTON --}}
        <x-lareon::buttons.simple variant="outline" size="xs" color="teal" x-on:click="$dispatch('open-modal', 'addPasskey')">
            {{ __('add') }}
        </x-lareon::buttons.simple>

        <x-lareon::modal name="addPasskey">

            <x-lareon::editor.input label="{{ __('passkey name') }}" placeholder="{{ __('e.g., MacBook Pro, iPhone') }}" name="name" x-ref="input" x-on:keydown.enter.prevent="register()"/>

            <p class="text-xs text-gray-600 mt-1">
                {{ __('give this passkey a name to help you identify it later') }}
            </p>


            {{-- ACTIONS --}}
            <div class="flex gap-3 mt-6">
                <x-lareon::buttons.simple size="xs" variant="solid" class="w-24" x-bind:disabled="loading || !name.trim()" x-on:click="register()">
                    <span x-show="!loading">{{ __('register') }}</span>
                    <span x-show="loading" x-cloak>...</span>
                </x-lareon::buttons.simple>

                <x-lareon::buttons.simple size="xs" variant="outline" class="w-24" x-on:click="reset()">
                    {{ __('cancel') }}
                </x-lareon::buttons.simple>
            </div>

        </x-lareon::modal>
    </div>
</x-lareon::panel-layout>
@pushonce('footerScripts')
    <script>
        document.addEventListener('alpine:init', () => {
            if (!window.Alpine) return;

            Alpine.data('passkeyManager', (config) => ({
                supported: false,
                name: '',
                loading: false,
                error: null,

                init() {
                    this.detectSupport();
                    this.name = this.defaultDeviceName();

                    window.addEventListener('passkeys:ready', () => {
                        this.detectSupport();
                    }, {once: true});
                },

                detectSupport() {
                    this.supported = Boolean(window.Passkeys?.isSupported?.());
                },

                defaultDeviceName() {
                    const ua = navigator.userAgent;

                    const pick = (list) =>
                        list.find(([r]) => r.test(ua))?.[1];

                    const browser = pick([
                        [/Edg|Edge/, 'Edge'],
                        [/OPR|Opera/, 'Opera'],
                        [/Firefox|FxiOS/, 'Firefox'],
                        [/Chrome|CriOS/, 'Chrome'],
                        [/Safari/, 'Safari'],
                    ]);

                    const os = pick([
                        [/iPhone/, 'iPhone'],
                        [/iPad|Macintosh(?=.*Mobile)/, 'iPad'],
                        [/Android/, 'Android'],
                        [/Mac/, 'Mac'],
                        [/Windows/, 'Windows'],
                    ]);

                    return [browser, os].filter(Boolean).join(' on ') || 'My Device';
                },

                async register() {
                    if (this.loading || !this.name.trim()) return;

                    this.loading = true;
                    this.error = null;

                    try {
                        await window.Passkeys.register({
                            name: this.name,
                            routes: config,
                            meta: {
                                device_name: this.name,
                                user_agent: navigator.userAgent
                            }
                        });

                        this.reset();

                    } catch (e) {

                        if (e?.name === 'UserCancelledError') return;

                        this.error = e?.message || 'Something went wrong';

                    } finally {
                        this.loading = false;
                    }
                },

                reset() {
                    this.name = '';
                    this.error = null;
                    this.loading = false;

                    this.$dispatch('close-modal');
                }
            }));

        });
    </script>
@endpushonce
