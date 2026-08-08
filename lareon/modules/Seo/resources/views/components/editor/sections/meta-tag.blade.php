@props(['data'=>[] ])
<section>
    <div class="bg-slate-50 p-6 bordering rounded-lg space-y-6">

        <div class="">
            <x-lareon::editor.input :required="false" labelPosition="start" :label="__('title')" name="seo[meta][title]" :value="$data['title'] ?? null" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('meta')])"/>
            <x-lareon::editor.input-counter min="50" max="60" name="seo[meta][title]"/>
        </div>
        <div>
            <x-lareon::editor.input-textarea :required="false" labelPosition="start" :label="__('description')" name="seo[meta][description]" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('description') , 'item'=>__('meta')])">{{$data['description'] ?? null}}</x-lareon::editor.input-textarea>
            <x-lareon::editor.input-counter min="150" max="165" name="seo[meta][description]"/>
        </div>
        <x-lareon::editor.input :required="false" labelPosition="start" :label="__('keywords')" name="seo[meta][keywords]" :value="implode('|',$data['keywords'] ?? [])" :placeholder="__('lareon::global.placeholders.separate.two',['attribute'=>__('keywords') , 'item'=>'|'])"/>

        <x-lareon::editor.input :required="false" labelPosition="start" :label="__('canonical url')" name="seo[meta][canonical_url]" :value="$data['canonical_url'] ?? null" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('canonical url')])"/>

        <x-lareon::editor.input-radio :default="1" :value="$data['indexable'] ?? 1" :options="[['label'=>__('yes') , 'value'=>1] ,['label'=>__('no') , 'value'=>0]]" labelPosition="start" :label="__('indexable')" name="seo[meta][indexable]"/>

        <x-lareon::editor.input-radio :default="1" :value="$data['followable'] ?? 1" :options="[['label'=>__('yes') , 'value'=>1] ,['label'=>__('no') , 'value'=>0]]" labelPosition="start" :label="__('followable')" name="seo[meta][followable]"/>
    </div>
</section>
