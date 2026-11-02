@props([
    'template' => null,
    'wrapperClass' => '',
])

@php
    $elements = $elements ?? [];
@endphp

<section @class(['space-y-6 y-box', $wrapperClass])>
    @forelse ($elements as $element)
        @php
            $props = $element['props'] ?? [];
            $name = $props['name'] ?? null;
            if (! is_string($name) || trim($name) === '')  continue;

            $elementId = $props['elementId'] ?? null;
            $templateId = $props['templateId'] ?? null;

            $arguments = $props['arguments'] ?? [];
            $view = $element['view'] ?? null;
        @endphp

        <x-lareon::editor.tabs.section>
            <input type="hidden" name="{{ $name }}[element_id]" value="{{ $elementId }}">
            <input type="hidden" name="{{ $name }}[template_id]" value="{{ $templateId }}">

            @includeWhen($view && view()->exists($view),$view,array_merge($props, is_array($arguments) ? $arguments : []) )
        </x-lareon::editor.tabs.section>

    @empty
        <div class="flex items-center justify-center bg-slate-50 p-6">
            {{ __('no data') }}
        </div>
    @endforelse
</section>
