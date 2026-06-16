@props(['menu'])
@can($menu['permission'] ?? null)
    @if(empty($menu['children']) || count(($menu['children'])) < 1)
       <x-lareon::aside.nav-link :menu="$menu"/>
    @else
        <x-lareon::aside.nav-item :menu="$menu"/>
    @endcan
@endif

