@props([
'type' => 'create', // create, edit, update, delete, restore
'instance' => null,
'publishInfo' => true,
'publishData' => true,
'action' => null,
'method' => null,
'hasTab' => true,
'id' => 'editor-form',
'hasFile' => false,
])

@php
    $type = strtolower(trim($type));

    $httpMethods = [
        'create' => 'POST',
        'store' => 'POST',
        'edit' => 'PATCH',
        'update' => 'PUT',
        'patch' => 'PATCH',
        'delete' => 'DELETE',
        'destroy' => 'DELETE',
        'restore' => 'POST',
    ];

    $method = $method ?? $httpMethods[$type] ?? 'POST';
    $isEditMode = in_array($type, ['edit', 'update', 'patch']);
    $isDeleteMode = in_array($type, ['delete', 'destroy']);
    $isCreateMode = in_array($type, ['create', 'store']);

    $buttonColor = match(true) {
        $isDeleteMode => 'delete',
        $isEditMode => 'update',
        default => 'create'
    };

    $buttonText = match(true) {
        $isDeleteMode => trans('lareon::global.buttons.delete'),
        $isEditMode => trans('lareon::global.buttons.update'),
        default => trans('lareon::global.buttons.create')
    };

    $buttonIcon = match(true) {
        $isDeleteMode => 'trash',
        $isEditMode => 'pen',
        default => 'plus'
    };

    if ($hasFile) {
        $formClasses .= " enctype='multipart/form-data'";
    }

   $styleClass = !$hasTab && View::hasSection('aside')
    ? 'md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-7'
    : '';
@endphp

<x-lareon::admin-layout>
    <x-slot:title> @yield('title') </x-slot:title>
    <x-slot:description> @yield('description') </x-slot:description>

    @yield('form.before')

    <form id="{{ $id }}" class="inner-content" method="{{ $method === 'GET' ? 'GET' : 'POST' }}" action="{{ $action ?? url()->current() }}" {{$hasFile ?  'enctype="multipart/form-data"' : ''}}>
        @csrf
        @method($method)
        @yield('form.start')

        <div class="grid grid-cols-1 gap-6 {{$styleClass}} ">
            <div class="md:col-span-2 lg:col-span-2 xl:col-span-5">
                <div class="space-y-6">
                    @hasSection('form')
                        @if($hasTab)
                            <x-lareon::editor.tabs.layout>
                                @yield('form')

                                @if($publishInfo || $publishData)
                                    <x-lareon::editor.tabs.item :title="__('publish data')">
                                        <div @class($publishInfo && $publishData ? 'grid gap-6 md:grid-cols-2': '')>
                                            @if($publishData)
                                                <x-lareon::editor.section.status-publish :instance="$instance"/>
                                            @endif
                                            @if($publishInfo)
                                                <x-lareon::editor.section.publish-data :instance="$instance"/>
                                            @endif
                                        </div>
                                    </x-lareon::editor.tabs.item>
                                @endif
                            </x-lareon::editor.tabs.layout>
                        @else
                            @yield('form')
                        @endif
                    @endif
                </div>
            </div>

            <aside class="xl:col-span-2">
                <div class="sticky top-6 space-y-6">
                    @hasSection('aside')
                        @yield ('aside')
                    @endif

                    @if($publishInfo &&  !$hasTab && $instance)
                        <x-lareon::editor.section.publish-data :instance="$instance"/>
                    @endif
                </div>
            </aside>
        </div>

        @yield('form.end')

        <div class="mt-6">
            <x-lareon::buttons.nav class="w-24" :fullWidth="false" type="submit" role="submit" :color="$buttonColor" :icon="$buttonIcon">
                {{ __($buttonText)}}
            </x-lareon::buttons.nav>
        </div>
    </form>
    @yield('form.after')
</x-lareon::admin-layout>


@props(['aside' => null,])

@if(isset($aside))
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-7">
        <main class="space-y-6 xl:col-span-5">
            {{ $slot }}
        </main>
        <aside class="space-y-6 xl:col-span-2">
            {{ $aside }}
        </aside>
    </div>
@else
    <div class="space-y-6">
        {{ $slot }}
    </div>
@endif

