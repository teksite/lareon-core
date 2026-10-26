@props(['items'=>[]])
<nav class="">
    <ul class="flex items-center gap-1 divide-x divider-line_light">
        @foreach($items as $title=>$url)
            @php
            $isActive = request()->fullUrl() === $url;
                @endphp
            <li class="px-3 py-1">
                <a href="{{$url}}" class="text-sm  {{$isActive ? 'text-second_color_dark active font-bold' :'text-slate-600'}}">
                    {{$title}}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
