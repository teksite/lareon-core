@props(['title', 'index' => 0])

<div x-show="activeTab === {{ $index }}" data-title="{{ $title }}" x-cloak class="tab-item">
    <section class="y-box">
        <fieldset class="fieldset space-y-6">
            <legend class="legend">{{$title}}</legend>
            {{ $slot }}
        </fieldset>
    </section>
</div>
