@props(['messages'=>null])
@if ($messages)
    <div>
        <ul {{ $attributes->merge(['class' => 'message-error space-y-1 mt-1']) }}>
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
    </div>
@endif
