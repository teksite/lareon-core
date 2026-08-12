@props(['name','value'=>[], 'required'=>false  ])

<fieldset class="fieldset">
    <legend class="legend">{{__('person page schema')}}</legend>
    <div class="space-y-6">
        <x-seo::editor.partials.person name="{{$name}}" :value="$value['person'] ?? []"/>
        <x-seo::editor.partials.same-as :name="$name" :value="$value['sameAs']?? []"/>
        <x-seo::editor.partials.organization :name="$name.'[worksFor]'" :value="$value['worksFor']['organization'] ?? []"  :title="__('works for')"/>
    </div>
</fieldset>
