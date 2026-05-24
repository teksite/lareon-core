@props(['menu'])
@php
    $href=isset($menu['route']) ? route($menu['route']) : ($menu['url'] ?? null);
@endphp
@can($menu['permission'] ?? null)
    @if(empty($menu['children']))
        <div class="rounded-e-xl p-2">
            <div class="flex items-center justify-start gap-2 w-full">
                <x-lareon::nav.accordion-item :item="$menu" size="18"/>
            </div>
        </div>
    @else
        <div class="rounded-e-xl p-2" x-data="{show: false}">
            <button type="button" class="flex items-center justify-start gap-2 w-full" @click="show=!show">
                <x-lareon::nav.accordion-item :item="$menu" size="18"/>
                <x-icon type="outline" icon="{{is_rtl() ? 'angle-left' :'angle-right'}}" size="10" class="w-3 fill-none stroke-gray-600 ms-auto me-0 " x-bind:class="show ? 'rotate-90' :''"/>
            </button>
            <ul x-show="show" x-collapse x-cloak class="*:flex *:items-center *:justify-start *:gap-2 space-y-1 p-2">
                @if($href)
                    @can($menu['permission'] ?? null)
                        <li>
                            <x-lareon::nav.accordion-item :item="$menu" size="28"/>
                        </li>
                    @endcan
                @endif
                @foreach($menu['children'] as $child)
                    @can($child['permission'] ?? null)
                        <li>
                            <x-lareon::nav.accordion-item :item="$child" size="10"/>
                        </li>
                    @endcan
                @endforeach
            </ul>
        </div>
    @endif
@endcan



