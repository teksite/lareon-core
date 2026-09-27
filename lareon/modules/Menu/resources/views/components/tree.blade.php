@php use Lareon\Modules\Menu\App\Logics\SubMenuLogic;use Lareon\Modules\Menu\App\Models\Menu; @endphp
@props(['menu'])
@php
    if(is_string($menu)) $menu=Menu::query()->firstWhere('label',$menu);
    $items = $menu instanceof Menu ? (new SubMenuLogic($menu))->allByMenu($menu)->result :[];
@endphp
@if(count($items))
    <x-menu::tree-items :items="$items"/>
@endif
