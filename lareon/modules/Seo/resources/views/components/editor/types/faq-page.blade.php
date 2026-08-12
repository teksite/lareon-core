@props(['name','value'=>[], 'required'=>false  ])
@php
    $finalName=$name."[FAQPage]";
@endphp
<fieldset class="fieldset space-y-6">
    <legend class="legend">{{__('faq schema')}}</legend>
    <x-seo::editor.partials.faq :name="$name" :value="$value['faq']?? []"/>
</fieldset>
