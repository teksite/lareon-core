@props(['items'=>[]])
<nav class="px-3 py-1">
    <ul class="flex items-center gap-6">
        @foreach($items as $title=>$url)
            @php
            $isActive = request()->fullUrl() === $url;
                @endphp
            <li>
                <a href="{{$url}}" class="text-sm {{$isActive ? 'text-blue-600 active font-bold' :'text-slate-600'}}">
                    {{$title}}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
