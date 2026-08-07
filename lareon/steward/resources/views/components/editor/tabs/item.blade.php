<fieldset class="@container tab-item bordering p-2 md:p-3 sm:p-6 rounded-xl" data-title="{{ $title }}" x-show="$parent.active===Array.from($el.parentElement.children).indexOf($el)" x-cloak>
    <legend class="font-bold px-3">{{ $title }}</legend>
    @if(isset($aside))
        <div class="flex flex-col @[900px]:flex-row gap-6">
            <div class="space-y-6 w-full">
                {{ $slot }}
            </div>

            <aside class="space-y-6 w-full @[900px]:max-w-[350px]">
                {{ $aside }}
            </aside>
        </div>
    @else
        <div class="space-y-6">
            {{ $slot }}
        </div>
    @endif
</fieldset>
