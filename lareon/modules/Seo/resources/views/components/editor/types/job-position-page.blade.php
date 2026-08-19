@props(['name','value'=>[], 'required'=>false  ])

<fieldset class="fieldset">
    <legend class="legend">{{__('job position page schema')}}</legend>
    <div class="space-y-6">
        <x-seo::editor.partials.identifier name="{{$name}}" :value="$value['identifier'] ?? []"/>
        <x-seo::editor.partials.job-position name="{{$name}}" :value="$value['jobPosition'] ?? []"/>
        <x-seo::editor.partials.monetary-amount name="{{$name}}[baseSalary]" :value="$value['baseSalary']['MonetaryAmount'] ?? []" :lable="__('baseSalary')"/>
        <x-seo::editor.partials.organization name="{{$name}}[hiringOrganization]" :value="$value['hiringOrganization']['organization'] ?? []"  :title="__('hiring organization')"/>
    </div>
</fieldset>
