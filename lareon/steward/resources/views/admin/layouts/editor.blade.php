@props([
    'id' => 'editor-form',
    'publishInfo' => true,
    'publishStatus' => true,
    'hasTab' => true,
    'hasMeta' => true,
    'hasSeo' => true,
])
@php
    $hasAside = \Illuminate\Support\Facades\View::hasSection('aside') || ($publishInfo && ! $hasTab && $instance);
    $styleClass = $hasAside ? 'flex flex-col lg:flex-row gap-6' : '';
@endphp

<x-lareon::admin-layout>
    <x-slot:title> @yield('title') </x-slot:title>
    <x-slot:description> @yield('description') </x-slot:description>

    @yield('form.before')

    <form id="{{ $id }}" class="inner-content" {{$formAttributes}}>
        @csrf
        @if($realMethod)
            @method($realMethod)
        @endif

        @yield('form.start')

        <div class="{{ $styleClass }}">
            <div class="w-full space-y-6">
                @hasSection('form')
                    <x-lareon::editor.tabs.layout :hasTab="$hasTab">
                        @yield('form')
                        @if($showTemplateSection)
                            <x-lareon::editor.tabs.item :title="__('meta data')">
                                <x-lareon::editor.tabs.section>
                                    <x-meta::elements-loader :template="$instance->template" :value="$instance->metaData"/>
                                </x-lareon::editor.tabs.section>
                            </x-lareon::editor.tabs.item>
                        @endif

                        @if($hasSeo)
                            <x-lareon::editor.tabs.item :title="__('seo')">
                                <x-lareon::editor.tabs.section>
                                    {{--                                    <x-meta::elements-loader :value="$instance->metaData"/>--}}
                                </x-lareon::editor.tabs.section>
                            </x-lareon::editor.tabs.item>
                        @endif


                        @if($publishInfo || $publishStatus)
                            <x-lareon::editor.tabs.item :title="__('publish data')">
                                <div @class(['grid gap-6 lg:grid-cols-2' => $publishInfo && $publishStatus])>
                                    @if($publishStatus)
                                        <x-lareon::editor.tabs.section>
                                            <x-lareon::editor.section.publish-status :instance="$instance"/>
                                        </x-lareon::editor.tabs.section>
                                    @endif
                                    @if($publishInfo)
                                        <x-lareon::editor.tabs.section>
                                            <x-lareon::editor.section.publish-info :instance="$instance"/>
                                        </x-lareon::editor.tabs.section>
                                    @endif
                                </div>
                            </x-lareon::editor.tabs.item>
                        @endif
                    </x-lareon::editor.tabs.layout>
                @endif
            </div>

            @if($hasAside)
                <aside class="w-full lg:max-w-[350px]">
                    <div class="sticky top-6 space-y-6">
                        @hasSection('aside')
                            @yield('aside')
                        @endif
                        @if($publishStatus)
                            <x-lareon::editor.section.publish-status :instance="$instance"/>
                        @endif
                        @if($publishInfo && ! $hasTab && $instance)
                            <x-lareon::editor.section.publish-info :instance="$instance"/>
                        @endif
                    </div>
                </aside>
            @endif
        </div>

        @yield('form.end')
        <div class="mt-6">
            <x-lareon::buttons.nav class="min-w-24" :fullWidth="false" type="submit" :color="$buttonColor" :icon="$buttonIcon">
                {{ $buttonTextKey }}
            </x-lareon::buttons.nav>
        </div>
    </form>

    @yield('form.after')
</x-lareon::admin-layout>
