<x-lareon::admin-layout>
    @section('title', __('captcha'))
    @section('description', __("on this page, you can enable or disable Maintenance Mode for the app"))

    <section class="w-full mx-auto lg:w-1/2">
        <x-lareon::box type="y">
            {{__('driver')}} : {{config('captcha.driver')}}
        </x-lareon::box>

    </section>

</x-lareon::admin-layout>
