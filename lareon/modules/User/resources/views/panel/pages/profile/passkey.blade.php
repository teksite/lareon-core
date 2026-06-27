<x-lareon::panel-layout>
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('profile')]) . "($user->fullname)")
    @section('nav' ,view('user::panel.pages.profile.partials.nav'))

    @if($passkeys->isNotEmpty())
        <x-lareon::box type="y" class="mb-6">
            <ul>
                @foreach($passkeys as $passkey)
                    <li class="flex justify-between items-center py-2">
                        <div>
                            <div class="font-medium">{{ $passkey->name }}</div>
                            <small class="text-gray-500">
                                <x-lareon::date :date="$passkey->last_used_at"/>
                            </small>
                        </div>

                        <x-lareon::links.action type="delete" method="delete" :href="route('panel.profile.passkeys.destroy', $passkey)" can="panel.profile.passkey"/>
                    </li>
                @endforeach
            </ul>
        </x-lareon::box>
    @endif


    <div
        x-data="passkeyManager({
            optionsUrl: @js(route('passkey.registration-options')),
            storeUrl: @js(route('passkey.store'))
        })"
        x-init="init()">

        <template x-if="!supported">
            <div class="text-sm text-red-600">
                {{ __('Passkeys are not supported in this browser.') }}
            </div>
        </template>


        <x-lareon::buttons.simple variant="outline" size="xs" color="teal" x-on:click="$dispatch('open-modal', 'addPasskey')">
            {{ __('add passkey') }}
        </x-lareon::buttons.simple>

        <x-lareon::modal name="addPasskey">

            <x-lareon::editor.input label="{{ __('passkey name') }}" placeholder="{{ __('e.g., MacBook Pro, iPhone') }}" x-model="name" x-ref="input" x-on:keydown.enter.prevent="register()"/>
            <p class="text-xs text-gray-500 mt-1">
                {{ __('give this passkey a name to help you identify it later') }}
            </p>
            <p x-show="error" x-text="error" x-cloak class="text-sm text-red-600 mt-2"></p>

            <div class="flex gap-3 mt-6">

                <x-lareon::buttons.simple size="xs" variant="solid" class="w-24" x-bind:disabled="loading" x-on:click="register()">
                    <span>{{ __('register') }}
                         <span x-show="loading" x-cloak>...</span>
                    </span>

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
            Alpine.data('passkeyManager', (config) => ({
                supported: false,
                name: '',
                loading: false,
                error: null,

                init() {
                    this.detectSupport();
                    this.name = this.generateDeviceName();

                    window.addEventListener('passkeys:ready', () => {
                        this.detectSupport();
                    }, {once: true});
                },

                detectSupport() {
                    this.supported = !!window.Passkeys?.isSupported?.();
                },

                generateDeviceName() {
                    const ua = navigator.userAgent;

                    const browser = [
                        [/Edg|Edge/, 'Edge'],
                        [/OPR|Opera|OPiOS/, 'Opera'],
                        [/Firefox|FxiOS/, 'Firefox'],
                        [/Chrome|CriOS/, 'Chrome'],
                        [/Safari/, 'Safari'],
                    ].find(([r]) => r.test(ua))?.[1];

                    const os = [
                        [/iPhone/, 'iPhone'],
                        [/iPad|Macintosh(?=.*Mobile)/, 'iPad'],
                        [/Android/, 'Android'],
                        [/Mac/, 'Mac'],
                        [/Windows/, 'Windows'],
                    ].find(([r]) => r.test(ua))?.[1];

                    return [browser, os].filter(Boolean).join(' on ') || 'My Device';
                },

                async register() {
                    if (!this.name.trim()) return;

                    this.loading = true;
                    this.error = null;

                    try {
                        await window.Passkeys.register({
                            name: this.name,
                            routes: {
                                options: config.optionsUrl,
                                submit: config.storeUrl,
                            }
                        });

                        this.reset();

                    } catch (e) {
                        if (e?.constructor?.name !== 'UserCancelledError') {
                            this.error = e?.message || 'Unknown error';
                        }
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
