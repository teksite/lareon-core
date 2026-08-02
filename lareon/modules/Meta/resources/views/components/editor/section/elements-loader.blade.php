@php use Lareon\Modules\Meta\App\Models\MetaTemplate; @endphp
@props(['template', 'wrapperMode'=>'y-box' ] )

@php

    $wrapperClass=match ($wrapperMode){
         'x-box'=>'x-box',
         'y-box'=>'y-box',
         default => null
     };
@endphp
<section class="{{ $wrapperClass . ' space-y-6'}}">
    @foreach($elements as $element)

        @includeIf($element['view'],$element['props'] )

    @endforeach
</section>
