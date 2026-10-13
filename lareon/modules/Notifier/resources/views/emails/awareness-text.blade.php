@if ($name)
    {{ __(':name, hello', ['name' => $name]) }}
@endif

{!! $title !!}

{!! $body !!}

--

{{ __('This message was automatically sent by :app_name.', ['app_name' => config('app.name')]) }}
