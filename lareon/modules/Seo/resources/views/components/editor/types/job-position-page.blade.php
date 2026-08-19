@props(['name','value'=>[], 'required'=>false  ])

<fieldset class="fieldset">
    <legend class="legend">{{__('job position page schema')}}</legend>
    <div class="space-y-6">
        <x-seo::editor.partials.identifier name="{{$name}}" :value="$value['identifier'] ?? []"/>
        <x-seo::editor.partials.job-position name="{{$name}}" :value="$value['jobPosition'] ?? []"/>
        <x-seo::editor.partials.monetary-amount name="{{$name}}[baseSalary]" :value="$value['baseSalary']['MonetaryAmount'] ?? []" :lable="__('baseSalary')"/>
        <x-seo::editor.partials.organization name="{{$name}}[hiringOrganization]" :value="$value['hiringOrganization']['organization'] ?? []" :title="__('hiring organization')"/>

        <fieldset class="fieldset" x-data="{ employmentType: '{{ $value['employmentType'] ?? 'in_site' }}'}">
            <legend class="legend">{{__('location')}}</legend>

            <div>
                <x-lareon::editor.input-select labelPosition="top" :label="__('location type')" name="{{$name}}[employmentType]" :value="$value['employmentType'] ?? null" :required="$required" x-model="employmentType">
                    @foreach(['in_site'=>'in site','remote'=>'remote'] as $key=>$desc)
                        <option value="{{$key}}">{{__($desc)}}</option>
                    @endforeach
                </x-lareon::editor.input-select>
            </div>

            <template x-if="employmentType === 'remote'">
                <x-seo::editor.partials.job-remote name="{{$name}}" :value="$value['applicantLocationRequirements'] ?? []" :name="$name"/>
            </template>

            <template x-if="employmentType === 'in_site'">
                <x-seo::editor.partials.address name="{{$name}}" :value="$value['place']['address'] ?? []" :name="$name.'[place]'"/>
            </template>

        </fieldset>
    </div>
</fieldset>
