@props([
    'value' => null,
    'disabled' => false,
    'required' => false,
    'name' => 'password',
    'confirm_name' => 'password_confirmation',
    'placeholder' => null,
    'strength' => true,
    'show_confirm' => true,
])

<div x-data="{
    show: false,
    showConfirm: false,
    password: '',
    confirmPassword: '',
    strength: 0,

    checkStrength() {
        let pass = this.password;
        if (!pass) {
            this.strength = 0;
            return;
        }

        let score = 0;
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

    get strengthText() {
        const texts = ['خیلی ضعیف', 'ضعیف', 'متوسط', 'قوی', 'خیلی قوی'];
        return texts[this.strength];
    },

    get passwordsMatch() {
        if (!this.confirmPassword) return true;
        return this.password === this.confirmPassword;
    }
}"
     class="space-y-3">

    {{-- فیلد اصلی پسورد --}}
    <div class="input flex items-center justify-between">
        <div class="relative flex-1">
            <input
                x-model="password"
                x-on:input="checkStrength()"
                name="{{ $name }}"
                type="password"
                x-bind:type="show ? 'text' : 'password'"
                class="w-full outline-none bg-transparent"
                placeholder="{{ $placeholder ?? 'پسورد' }}"
                @required($required)
                {{ $disabled ? 'disabled' : '' }}
                autocomplete="new-password"
            >

            {{-- نشانگر قدرت --}}
            <div x-show="strength > 0 && password" x-cloak class="absolute -bottom-5 left-0 right-0">
                <div class="flex gap-1 mt-1">
                    <template x-for="i in 4">
                        <div class="flex-1 h-1 rounded-full transition-all"
                             :class="i <= strength ? strengthColor : 'bg-gray-200'">
                        </div>
                    </template>
                </div>
                <span class="text-xs" :class="strength >= 3 ? 'text-green-600' : 'text-gray-500'" x-text="strengthText"></span>
            </div>
        </div>

        <button type="button" @click="show = !show" class="p-1">
            <svg x-show="!show" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <svg x-show="show" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            </svg>
        </button>
    </div>

    {{-- فیلد تکرار پسورد --}}
    @if($show_confirm)
        <div class="input flex items-center justify-between">
            <input
                x-model="confirmPassword"
                name="{{ $confirm_name }}"
                type="password"
                x-bind:type="showConfirm ? 'text' : 'password'"
                class="w-full outline-none bg-transparent"
                placeholder="تکرار پسورد"
                @required($required)
                {{ $disabled ? 'disabled' : '' }}
                autocomplete="new-password"
            >

            <button type="button" @click="showConfirm = !showConfirm" class="p-1">
                <svg x-show="!showConfirm" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="showConfirm" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        </div>

        <div x-show="confirmPassword && !passwordsMatch" x-cloak>
            <p class="text-red-500 text-xs">رمز عبور با تکرار آن مطابقت ندارد</p>
        </div>
    @endif

    @error($name)
    <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror
    @error($confirm_name)
    <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror
</div>
