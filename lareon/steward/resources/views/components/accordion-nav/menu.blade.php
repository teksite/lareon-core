@props(['menu'])
@can($menu['permission'] ?? null)
    @if(empty($menu['children']) || count(($menu['children'])) < 1)
        <div class="rounded-e-xl p-2">
            <div class="flex items-center justify-start gap-2 w-full">
                <x-lareon::accordion-nav.link :menu="$menu" size="18"/>
            </div>
        </div>
    @else
      <x-lareon::accordion-nav.item :menu="$menu"/>
    @endif
@endcan



