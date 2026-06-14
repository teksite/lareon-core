@props([
    'data' => [],
    'thead' => null,
    'tfoot' => null,
])
@once
    @php

        function renderValue($value)
        {
            return renderValueRecursive($value);
        }

        function renderValueRecursive($value)
        {
            // NULL
            if (is_null($value)) {
                return '<span class="text-gray-400">null</span>';
            }

            // BOOLEAN
            if (is_bool($value)) {
                return '<x-icon type="outline"
                    icon="'.($value ? 'tick-circle' : 'cross').'"
                    class="fill-none stroke-2 '.($value ? 'stroke-green-600' : 'stroke-red-500').'"
                    size="12"/>';
            }

            // STRING / NUMBER
            if (is_string($value) || is_numeric($value)) {
                return e($value);
            }

            // ARRAY
            if (is_array($value)) {

                // list array
                $isList = array_keys($value) === range(0, count($value) - 1);

                if ($isList) {
                    $html = '<ul class="pl-4 list-disc space-y-1">';
                    foreach ($value as $item) {
                        $html .= '<li>' . renderValueRecursive($item) . '</li>';
                    }
                    return $html . '</ul>';
                }

                // associative array
                $html = '<div class="space-y-1">';
                foreach ($value as $k => $v) {
                    $html .= '
                        <div class="flex gap-2">
                            <span class="font-semibold">'.$k.':</span>
                            <span>'.renderValueRecursive($v).'</span>
                        </div>
                    ';
                }
                return $html . '</div>';
            }

            // OBJECT
            if (is_object($value)) {
                return renderValueRecursive(get_object_vars($value));
            }

            return e((string)$value);
        }

    @endphp
@endonce
<table class="w-full border-collapse">

    {{-- THEAD --}}
    @if($thead)
        <thead>
        <tr>
            @foreach($thead as $head)
                <th class="p-3 text-left font-bold border-b border-line_light">
                    {{ $head }}
                </th>
            @endforeach
        </tr>
        </thead>
    @endif

    {{-- TBODY --}}
    <tbody>
    @foreach($data as $key => $value)

        {{-- SIMPLE VALUE ROW --}}
        @if(!is_array($value))
            <tr class="border-b border-line_light">
                <td class="p-3 font-bold">
                    {{ $key }}
                </td>

                <td class="p-3" colspan="{{ $thead ? count($thead) - 1 : 1 }}">
                    {!! renderValue($value) !!}
                </td>
            </tr>

            {{-- NESTED ARRAY ROW --}}
        @else
            <tr class="border-b border-line_light">
                <td class="p-3 font-bold align-top" rowspan="{{ count($value) }}">
                    {{ $key }}
                </td>

                <td class="p-3 font-semibold">
                    {{ array_key_first($value) }}
                </td>

                <td class="p-3">
                    {!! renderValue(reset($value)) !!}
                </td>
            </tr>

            @foreach(array_slice($value, 1, null, true) as $k => $v)
                <tr class="border-b border-line_light">
                    <td class="p-3 font-semibold">
                        {{ $k }}
                    </td>

                    <td class="p-3">
                        {!! renderValue($v) !!}
                    </td>
                </tr>
            @endforeach
        @endif

    @endforeach
    </tbody>

    {{-- TFOOT --}}
    @if($tfoot)
        <tfoot>
        <tr>
            @foreach($tfoot as $foot)
                <td class="p-3 font-bold border-t border-line_light">
                    {{ $foot }}
                </td>
            @endforeach
        </tr>
        </tfoot>
    @endif

</table>


