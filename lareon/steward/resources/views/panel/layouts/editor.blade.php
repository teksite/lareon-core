@props([
    'id' => 'editor-form',
    'hasTab' => true,
    'hasMeta' => true,
    'hasSeo' => true,
])

@php
    $styleClass=config('lareon.admin.layout.editor')=== 'two_column' ? 'md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-7' : '';
@endphp

<x-lareon::panel-layout>
    <x-slot:title> @yield('title') </x-slot:title>
    <x-slot:description> @yield('description') </x-slot:description>


    @yield('form.before')

    <form id="{{ $id }}" class="inner-content" {{$formAttributes}} novalidate>
        @csrf
        @if($realMethod)
            @method($realMethod)
        @endif
        @isset($instance)
            <input type="hidden" name="model" value="{{encrypt(get_class($instance ))}}">
            <input type="hidden" name="model_key" value="{{encrypt($instance?->getKey())}}">
        @endisset
        <div class="grid grid-cols-1 gap-6 {{$styleClass}} ">
            <div class="md:col-span-2 lg:col-span-2 xl:col-span-5">
                <div class="space-y-6">
                    @yield('form.start')
                    <div class="space-y-6">
                        @hasSection('form')
                            @if($hasTab)
                                <x-lareon::editor.tabs.layout>
                                    @yield('form')
                                    @if($publishStatus && !$isDeleteMode && $instance)
                                        <x-lareon::editor.tabs.item :title="__('publish data')">
                                            <x-lareon::editor.section.publish-info :instance="$instance"/>
                                        </x-lareon::editor.tabs.item>
                                    @endif
                                </x-lareon::editor.tabs.layout>
                            @else
                                @yield('form')
                            @endif
                        @endif
                    </div>
                    @yield('form.end')
                </div>

                <div id="form-error-summary" class="hidden mb-6 p-4 rounded-xl border border-red-300 bg-red-50">
                    <p class="font-semibold text-red-700 mb-2">{{ __('لطفاً موارد زیر را بررسی کنید') }}:</p>
                    <ul id="form-error-list" class="list-disc list-inside space-y-1 text-red-600 text-sm"></ul>
                </div>
            </div>

            <aside class="xl:col-span-2">
                <div class="sticky top-6 space-y-6">
                    <div class="mt-6">
                        <x-lareon::buttons.nav class="min-w-36" :fullWidth="false" type="submit" :color="$buttonColor" :icon="$buttonIcon">
                            {{ $buttonTextKey }}
                        </x-lareon::buttons.nav>
                    </div>
                </div>
            </aside>
        </div>
    </form>
    @yield('form.after')

</x-lareon::panel-layout>
