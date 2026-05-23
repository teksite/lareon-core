@props(['messages'=>null])
@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1 mt-1']) }}>
        @foreach ((array) $messages as $message)
           @if($message)
                @foreach ((array) $message as $msg)
                    {{$msg}}
                @endforeach
            @else
                {{$message}}
            @endif
        @endforeach
    </ul>
@endif
