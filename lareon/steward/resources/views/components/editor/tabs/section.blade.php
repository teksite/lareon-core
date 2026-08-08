@props(['class'=>'space-y-6 y-box'])
<section {{$attributes->merge(['class'=> $class])}}>
    {!! $slot !!}
</section>
