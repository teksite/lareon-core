@props(['data'=>[] ])
@php
    $name='seo[schema]';
    $data=collect($data)->toArray();
@endphp
<section>
    <div class="bg-slate-50 p-6 bordering rounded-lg space-y-6">
        <x-seo::editor.types.article name="{{$name}}" :value="$data['schema'] ?? []"/>
    </div>
</section>
