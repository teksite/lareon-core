@props(['items'])
@if(count($items))
    <ul>
        @foreach($items as $item)
            <li>
                {{$item->title}}
                @if($item->children)
                    <x-menu::tree-items :items="$item->children"/>
                @endif
            </li>
        @endforeach
    </ul>
@endif
