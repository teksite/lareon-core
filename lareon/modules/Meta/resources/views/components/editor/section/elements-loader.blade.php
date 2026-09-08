@props(['template'=>null, 'wrapperClass'=>null ] )

<section class="{{ $wrapperClass . ' space-y-6'}}">
    @if(count($elements))
        @foreach($elements as $element)
            <x-lareon::editor.tabs.section>
                @includeIf($element['view'],$element['props'] ,[...$element['props']['arguments'] ?? []] )
            </x-lareon::editor.tabs.section>
        @endforeach
    @else
        <div class="flex items-center justify-center bg-slate-50 p-6">
            {{__('no data')}}
        </div>
    @endif
</section>
