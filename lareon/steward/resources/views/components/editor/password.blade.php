@props([
    'value' => null,
    'disabled' => false,
    'required' => false,
    'name' => 'password',
    'confirmName' => 'password_confirmation',
    'placeholder' => null,
    'strength' => true,
    'showConfirm' => true,
    'label' => null,
    'confirmLabel' => null,
    'wrapperClass' => null,
])

@php
    $dotName = str_replace(['[', ']'], ['.', ''], $name);
    $dotConfirmName = str_replace(['[', ']'], ['.', ''], $confirmName);

    $id = $dotName;
    $confirmId = $dotConfirmName;

    $oldValue = $value;

    $errorMessage = $errors->first($dotName);
    $errorConfirmMessage = $errors->first($dotConfirmName);

    $errorClass = $errorMessage ? 'input-error' : '';
    $errorConfirmClass = $errorConfirmMessage ? 'input-error' : '';

    $placeholderText = $placeholder ?? __('password');
    $confirmPlaceholder = trans('lareon::global.placeholders.write.auth.confirm_password');

    $labelText = $label ;
    $confirmLabelText = $confirmLabel ??  __('confirm :attribute', ['attribute' => __('password')]);
@endphp

<div
    x-data="passwordInput()"
    x-init="init('{{ addslashes($oldValue) }}')"
    class="{{ $wrapperClass }}"
    {{ $attributes->whereStartsWith('x-on:') }}
    {{ $attributes->whereStartsWith('@') }}
>
    <div class="mb-3">
        @if($labelText)
            <x-lareon::inputs.label :title="$labelText" :for="$id" class="mb-1" :markAsRequire="$required" />
        @endif

        <div class="input flex items-center justify-between gap-2 {{ $errorClass }}">
            <div class="relative flex-1">
                <input type="password" id="{{ $id }}" name="{{ $name }}" value="{{ $oldValue }}" autocomplete="new-password" placeholder="{{ $placeholderText }}" x-model="password" @input="checkStrength()" :type="show ? 'text' : 'password'" class="w-full outline-none bg-transparent"@required($required)@disabled($disabled)>

                @if($strength)
                    <div x-show="strength > 0 && password.length > 0" x-cloak class="absolute left-0 right-0 -bottom-8">
                        <div class="flex gap-1 mt-1">
                            <template x-for="i in 4" :key="i">
                                <div class="flex-1 h-1 rounded-full transition-all duration-300" :class="i <= strength ? strengthColor : 'bg-gray-200'"></div>
                            </template>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-xs font-medium" :class="strengthTextColor" x-text="strengthText"></span>
                            <span class="text-xs text-gray-400" x-text="password.length + ' characters'"></span>
                        </div>
                    </div>
                @endif
            </div>

            <button type="button" @click="toggleShow()" class="p-1 flex-shrink-0">
                <svg x-show="!show" class="w-5 h-5 text-gray-500 hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg x-show="show" class="w-5 h-5 text-gray-500 hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            </button>
        </div>

        <x-lareon::inputs.error :messages="$errorMessage" />
    </div>

    @if($showConfirm)
        <div>
            @if($confirmLabelText)
                <x-lareon::inputs.label :title="$confirmLabelText" :for="$confirmId" class="mb-1" :required="$required"/>
            @endif

            <div class="input flex items-center justify-between gap-2 {{ $errorConfirmClass }}">
                <input type="password" id="{{ $confirmId }}" name="{{ $confirmName }}" autocomplete="new-password" placeholder="{{ $confirmPlaceholder }}" x-model="confirmPassword" :type="showConfirm ? 'text' : 'password'" class="w-full outline-none bg-transparent"@required($required)@disabled($disabled)>

                <button type="button" @click="toggleShowConfirm()" class="p-1 flex-shrink-0">
                    <svg x-show="!showConfirm" class="w-5 h-5 text-gray-500 hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="showConfirm" class="w-5 h-5 text-gray-500 hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>

            <x-lareon::inputs.error :messages="$errorConfirmMessage" />

            <div x-show="confirmPassword.length > 0 && !passwordsMatch" x-cloak class="mt-1 text-xs text-red-600 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ __('passwords do not match') }}</span>
            </div>

            <div x-show="confirmPassword.length > 0 && passwordsMatch && password.length > 0" x-cloak class="mt-1 text-xs text-green-600 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ __('passwords match') }}</span>
            </div>
        </div>
    @endif
</div>

{{-- Alpine.js Component --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('passwordInput', () => ({
            show: false,
            showConfirm: false,
            password: '',
            confirmPassword: '',
            strength: 0,

            init(initialValue = '') {
                this.password = initialValue;
                if (initialValue) {
                    this.checkStrength();
                }
            },

            toggleShow() {
                this.show = !this.show;
            },

            toggleShowConfirm() {
                this.showConfirm = !this.showConfirm;
            },

            checkStrength() {
                let pass = this.password;
                if (!pass) {
                    this.strength = 0;
                    return;
                }

                let score = 0;

                // طول
                if (pass.length >= 8) score++;
                if (pass.length >= 12) score++;

                if (/[a-z]/.test(pass) && /[A-Z]/.test(pass)) score++;

                if (/[0-9]/.test(pass)) score++;

                if (/[^a-zA-Z0-9]/.test(pass)) score++;

                this.strength = Math.min(score, 4);
            },

            get strengthColor() {
                const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
                return colors[this.strength];
            },

            get strengthTextColor() {
                const colors = ['text-red-600', 'text-orange-600', 'text-yellow-600', 'text-blue-600', 'text-green-600'];
                return colors[this.strength];
            },

            get strengthText() {
                const texts = ['very Weak', 'weak', 'medium', 'strong', 'very strong'];
                return texts[this.strength];
            },

            get passwordsMatch() {
                if (!this.confirmPassword) return true;
                return this.password === this.confirmPassword;
            }
        }));
    });
</script>
