@props (['body'=>[]])
<table class="w-full border-collapse">
    @foreach($body as $key => $value)
        @if(!is_array($value))
            <tr class="border-b border-line_light">
                <td class="p-3 font-bold">
                    {{ $key }}
                </td>
                <td class="p-3" colspan="2">
                    @if(is_bool($value))
                        <x-icon type="outline" icon="{{$value ? 'tick-circle' : 'cross'}}" class="fill-none stroke-2 {{$value ? 'stroke-green-600' : 'stroke-yellow-600'}}" size="12"/>
                    @else
                        {{$value}}
                    @endif

                </td>
            </tr>

        @else
            <tr class="border-b border-line_light">
                <td class="p-3 font-bold align-top" rowspan="{{ count($value) + 1 }}">
                    {{ $key }}
                </td>
            </tr>
            @foreach($value as $k => $v)
                <tr class="border-b border-line_light">
                    <td class="p-3">
                        {{ $k }}
                    </td>

                    <td class="p-3">
                        @if(is_bool($v))
                            <x-icon type="outline" icon="{{$v ? 'tick-circle' : 'cross'}}" class="fill-none stroke-2 {{$v ? 'stroke-green-600' : 'stroke-yellow-600'}}" size="12"/>
                        @else
                            {{$v}}
                        @endif
                    </td>
                </tr>
            @endforeach

        @endif

    @endforeach
</table>
