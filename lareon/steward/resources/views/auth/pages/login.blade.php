<x-lareon::auth-layout>
    <!-- Session Status -->
    {{--    <x-auth-session-status class="mb-4" :status="session('status')" />--}}

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-lareon::editor.input :label="__('email')" name="email" :required="true"/>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-lareon::editor.input-password name="password" :label="__('password')" :strength="false" :showConfirm="false" :required="true"/>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-lareon::buttons.simple type="submit" role="submit" :fullWidth="true">
                {{__('lareon::global.buttons.sign_in')}}
            </x-lareon::buttons.simple>
        </div>
    </form>
</x-lareon::auth-layout>
