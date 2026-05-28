@props([
'type' => 'create', // create, edit, update, delete, restore
'instance' => null,
'publishStatus' => true,
'route' => null,
'method' => null,
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
        $isDeleteMode => 'red',
        $isEditMode => 'blue',
        default => 'green'
    };

    $buttonText = match(true) {
        $isDeleteMode => __('delete'),
        $isEditMode => __('update'),
        default => __('create')
    };

    $buttonIcon = match(true) {
        $isDeleteMode => 'trash',
        $isEditMode => 'pen',
        default => 'plus'
    };

    $formClasses = "w-full";
    if ($hasFile) {
        $formClasses .= " enctype='multipart/form-data'";
    }
@endphp

<x-lareon::admin-layout>
    <x-slot:title>
        @yield('title')
    </x-slot:title>

    <x-slot:description>
        @yield('description')
    </x-slot:description>

    @yield('form.before')

    <form id="{{ $id }}" class="{{ $formClasses }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}" action="{{ $route ?? url()->current() }}" {{$hasFile ?  'enctype="multipart/form-data"' : ''}}>
        @csrf
        @method($method)

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-7">
            <div class="md:col-span-2 lg:col-span-2 xl:col-span-5">
                <div class="space-y-6">
                    @yield('form.before.start')
                    <div class="space-y-6">
                        @yield('form')
                    </div>
                    @yield('form.before.end')
                </div>
            </div>

            <aside class="xl:col-span-2">
                <div class="sticky top-6 space-y-6">
                    @hasSection('aside')
                        @yield('aside')
                    @endif

                    @if($publishStatus && !$isDeleteMode)
                        <x-lareon::form.status-publish :instance="$instance" :is-create-mode="$isCreateMode"/>
                    @endif

                    <div class="mt-6">
                        <x-lareon::button.simple type="submit" role="submit" :color="$buttonColor" :icon="$buttonIcon" class="w-full justify-center">
                            {{ $buttonText }}
                        </x-lareon::button.simple>

                        @if(!$isCreateMode && !$isDeleteMode)
                            <x-lareon::button.simple type="button" color="gray" class="w-full justify-center mt-2" onclick="window.history.back()">
                                {{ __('cancel') }}
                            </x-lareon::button.simple>
                        @endif
                    </div>

                    {{-- اطلاعات اضافی برای حالت ویرایش --}}
                    @if($isEditMode && $instance)
                        <x-lareon::editor.meta-info :instance="$instance"/>
                    @endif
                </div>
            </aside>
        </div>
    </form>
    @yield('form.after')

</x-lareon::admin-layout>
