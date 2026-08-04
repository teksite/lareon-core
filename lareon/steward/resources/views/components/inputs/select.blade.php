@props([
    'selected' => null,
    'disabled' => false,
    'required' => false,
    'multiple' => false,
    'placeholder' => null,
    'inline' => false,
])
@php
    $selectedValues = is_array($selected) ? $selected : (array) $selected;
    $classes = 'input block w-full';
    if ($multiple) $classes .= ' multiple-select';

    $innerHtml = '';

    if ($slot->isNotEmpty()) {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?><div>' . $slot . '</div>',    LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        foreach ($dom->getElementsByTagName('option') as $option) {
            $value = $option->hasAttribute('value')
                ? $option->getAttribute('value')
                : trim($option->textContent);

            if (in_array($value, $selectedValues, false)) {
                $option->setAttribute('selected', 'selected');
            } else {
                $option->removeAttribute('selected');
            }
        }

        $wrapper = $dom->getElementsByTagName('div')->item(0);
        foreach ($wrapper->childNodes as $child) {
            $innerHtml .= $dom->saveHTML($child);
        }
    }
@endphp

<select
    @required($required)
    {{ $disabled ? 'disabled' : '' }}
    {{ $multiple ? 'multiple' : '' }}
    {{ $attributes->merge(['class' => $classes]) }}
    {{ $inline ? 'data-inline' : '' }}
>
    @if($placeholder && !$multiple)
        <option value="">{{ $placeholder }}</option>
    @endif

    {!! $innerHtml !!}
</select>
