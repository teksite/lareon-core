@props(['title', 'index' => 0])

<div x-show="activeTab === {{ $index }}" data-title="{{ $title }}" x-cloak class="tab-item">
    <x-lareon::box type="y">
        <fieldset class="fieldset space-y-6">
            <legend class="legend">{{$title}}</legend>
            {{ $slot }}
        </fieldset>
    </x-lareon::box>
</div>
