@props(['title'])

<fieldset class="tab-item bordering p-6" data-title="{{ $title }}" x-show="$parent.active===Array.from($el.parentElement.children).indexOf($el)" x-cloak>
    <legend class="font-bold ">{{$title}}</legend>
    @if(isset($aside))
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-7">
            <main class="space-y-6 xl:col-span-5 ">
                {{ $slot }}
            </main>
            <aside class="space-y-6 xl:col-span-2">
                {{ $aside }}
            </aside>
        </div>
    @else
        <div class="space-y-6 y-box">
            {{ $slot }}
        </div>
    @endif
</fieldset>
