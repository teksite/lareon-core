@props(['selected'=>null, "disabled"=>false ,'required'=>false ,'multiple'=>false, 'options' =>[] ,'placeholder'=>null , 'inline'=>false])
@php
    $selectedValues = is_array($selected) ? $selected : (array)$selected;
    $classes = 'input block w-full';
    if ($multiple) $classes .= ' multiple-select';

    $slotContent = null;

    if (isset($slot) && !$slot->isEmpty()) {
        $html = (string) $slot;

        if (!empty($selectedValues)) {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML(
                '<?xml encoding="utf-8" ?><div>' . $html . '</div>',
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
            );
            libxml_clear_errors();

            foreach ($dom->getElementsByTagName('option') as $option) {
                $value = $option->getAttribute('value');

                if ($value === '' && !$option->hasAttribute('value')) {
                    $value = trim($option->textContent);
                }

                if (in_array($value, $selectedValues, false)) {
                    $option->setAttribute('selected', 'selected');
                } else {
                    $option->removeAttribute('selected');
                }
            }

            $wrapper = $dom->getElementsByTagName('div')->item(0);
            $slotContent = '';
            foreach ($wrapper->childNodes as $child) {
                $slotContent .= $dom->saveHTML($child);
            }
        } else {
            $slotContent = $html;
        }
    }
@endphp

<select @required($required) {{ $disabled ? 'disabled' : '' }} {{ $multiple ? 'multiple' : '' }} {{ $attributes->merge(['class' => $classes]) }} {{ $inline ? 'data-inline' : '' }}>
    @if($placeholder && !$multiple)
        <option value="">{{ $placeholder }}</option>
    @endif

    @if($slotContent !== null)
        {!! $slotContent !!}
    @else
        @foreach($options as $option)
            @php
                if (is_array($option)) {
                    $value = array_key_first($option);
                    $label = reset($option);
                } else {
                    $value = $option;
                    $label = $option;
                }
            @endphp
            <option value="{{ $value }}" @selected(in_array($value, $selectedValues))>{{ $label }}</option>
        @endforeach
    @endif
</select>
