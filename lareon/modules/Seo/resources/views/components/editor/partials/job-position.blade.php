@props(['name','value'=>[], 'required'=>false , 'arrayName'=>'event' ,'title'=>__('job position')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <div class="grid gap-6 md:grid-cols-2">
            <x-lareon::editor.input :label="__('job title')" name="{{$finalName}}[title]" :value="$value['title'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])"/>
            <x-lareon::editor.input :label="__('industry')" name="{{$finalName}}[industry]" :value="$value['industry'] ?? null" labelPosition="top" :required="false"/>
        </div>
        <x-lareon::editor.input-textarea :label="__('description')" name="{{$finalName}}[description]" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>

        <div class="grid gap-6 md:grid-cols-2">
            <x-lareon::editor.input type="text" :label="__('work hours')" name="{{$finalName}}[workHours]" :value="$value['workHours'] ?? null" labelPosition="top" :required="false" placeholder="(e.g. 8am-5pm, shift)"/>

            <x-lareon::editor.input-select labelPosition="top" :label="__('employmentType')" name="{{$finalName}}[employmentType]" :value="$value['employmentType'] ?? null" :required="$required">
                @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('employment_type_list') as $key=>$desc)
                    <option value="{{$key}}">{{__($desc)}}</option>
                @endforeach
            </x-lareon::editor.input-select>
        </div>
        <div class="grid gap-6 md:grid-cols-2">
            <x-lareon::editor.input type="date" :label="__('start date')" name="{{$finalName}}[datePosted]" :value="$value['startDate'] ?? null" labelPosition="top" :required="$required"/>
            <x-lareon::editor.input type="date" :label="__('valid through')" name="{{$finalName}}[validThrough]" :value="$value['validThrough'] ?? null" labelPosition="top" :required="$required"/>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <x-lareon::editor.input :label="__('responsibilities')" name="{{$finalName}}[responsibilities]" :value="$value['responsibilities'] ?? null" labelPosition="top" :required="false" />
            <x-lareon::editor.input :label="__('skills')" name="{{$finalName}}[skills]" :value="$value['skills'] ?? null" labelPosition="top" :required="false"/>
            <x-lareon::editor.input :label="__('qualifications')" name="{{$finalName}}[qualifications]" :value="$value['qualifications'] ?? null" labelPosition="top" :required="false"/>
            <x-lareon::editor.input :label="__('education requirements')" name="{{$finalName}}[educationRequirements]" :value="$value['educationRequirements'] ?? null" labelPosition="top" :required="false"/>
            <x-lareon::editor.input :label="__('experience requirements')" name="{{$finalName}}[experienceRequirements]" :value="$value['experienceRequirements'] ?? null" labelPosition="top" :required="false"/>
        </div>
    </div>
</fieldset>
