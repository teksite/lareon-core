@props(['title'=>null])

<section class="y-box">
    @if($title)
        <fieldset class="fieldset">
            <legend class="legend">{{ $title }}</legend>
            <div class="space-y-6">
                {{ $slot }}
            </div>
        </fieldset>
    @else
        {{ $slot }}
    @endif
</section>
