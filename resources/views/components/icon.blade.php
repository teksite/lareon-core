<svg x="{{$x}}" y="{{$y}}" width="{{$width}}" height="{{$height}}" viewBox="{{$viewbox}}" {{$attributes->merge(['class'=>'tkicon '.$icon]) }} data-icon="{{$icon}}" stroke-width="{{$strokeWidth}}" stroke-linecap="{{$strokeLinecap}}" stroke-linejoin="{{$strokeLinejoin}}">
    {{ $title && strlen($title) ? "<title>$itlte</title>" : '' }}
    {!! $path !!}
</svg>
