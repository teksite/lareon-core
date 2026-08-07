@props(['template'=>null, 'wrapperClass'=>null ] )

<section class="{{ $wrapperClass . ' space-y-6'}}">
    @if(count($elements))
        @foreach($elements as $element)
            @includeIf($element['view'],$element['props'] )
        @endforeach
    @else
        <div class="flex items-center justify-center">
            {{__('no data')}}
        </div>
    @endif
</section>
