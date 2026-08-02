@props(['title', 'name'  ,'elementId' ,'value'=>[] ,'open'=>false ,'accordion'=>false] )
@php($randomItem=rand(100,9999).\Illuminate\Support\Str::random(6).rand(100,9999))
<x-lareon::accordion.single :title="__($title)" :open="$open" :accordion="$accordion">
    <input type="hidden" name="{{$name}}[element_id]" value="{{$elementId}}">
    <div>
        <div class="grid gap-3 md:grid-cols-2">
            <div class="w-full">
                <x-lareon::inputs.label :title="__('title')" for="dynamic_title-{{$randomItem}}"/>
                <x-lareon::inputs.text name="{{$name}}[data][title]" id="dynamic_title-{{$randomItem}}}" class="block w-full" :value="$value['title'] ?? ''"/>
            </div>
            <div class="w-full">
                <x-lareon::inputs.label :title="__('image')" for="dynamic_image-{{$randomItem}}"/>
                <x-lareon::inputs.text name="{{$name}}[data][image]" id="dynamic_image-{{$randomItem}}}" class="block w-full" :value="$value['image'] ?? ''" dir="ltr"/>
            </div>
        </div>
        <div>
            <x-lareon::inputs.label :title="__('content')" for="dynamic_content-{{$randomItem}}"/>
            <x-lareon::inputs.textarea name="{{$name}}[data][content]" id="dynamic_content-{{$randomItem}}}" class="block w-full">{{$value['content'] ?? ''}}</x-lareon::inputs.textarea>
        </div>

        <div class="grid gap-3 md:grid-cols-2">
            <div class="w-full">
                <x-lareon::inputs.label :title="__('link title')" for="dynamic_link_title-{{$randomItem}}"/>
                <x-lareon::inputs.text name="{{$name}}[data][link_title]" id="dynamic_link_title-{{$randomItem}}}" class="block w-full" :value="$value['link_title'] ?? ''"/>
            </div>
            <div class="w-full">
                <x-lareon::inputs.label :title="__('link url')" for="dynamic_link_url-{{$randomItem}}"/>
                <x-lareon::inputs.text name="{{$name}}[data][link_url]" id="dynamic_link_url-{{$randomItem}}}" class="block w-full" :value="$value['link_url'] ?? ''" dir="ltr"/>
            </div>

        </div>

    </div>
    <fieldset class="fieldset">
        <legend class="legend">
            {{$title}}
        </legend>
        <div>
            @foreach($value['items'] ?? [] as $key=>$item)
                @php($rand=Illuminate\Support\Str::random(6).rand(100,9999))
                <div class="dynamicGroup_{{$randomItem}} border border-zinc-600 mb-3 rounded-lg p-3" id="content-content-{{$rand}}-{{$loop->index}}" x-data="{ removeField() { document.getElementById('content-content-{{$rand}}-{{$loop->index}}').remove(); }}">
                    <div class=" mb-3 flex justify-between items-center gap-6">
                        <div class="w-full md:grid-cols-2 gap-3 grid">
                            <div class="w-full">
                                <x-lareon::inputs.label :title="__('title') . ' #'. ($loop->iteration)" for="dynamic_item_title-{{$rand}}-{{$loop->index}}"/>
                                <x-lareon::inputs.text name="{{$name}}[data][items][{{$loop->index}}][title]" id="dynamic_item_title-{{$rand}}-{{$loop->index}}" class="block w-full" :value="$item['title'] ?? ''"/>
                            </div>
                            <div class="w-full">
                                <x-lareon::inputs.label :title="__('image') . ' #'. ($loop->iteration)" for="dynamic_item_image-{{$rand}}-{{$loop->index}}"/>
                                <x-lareon::inputs.text name="{{$name}}[data][items][{{$loop->index}}][image]" id="dynamic_item_image-{{$rand}}-{{$loop->index}}" class="block w-full" :value="$item['image'] ?? ''" dir="ltr"/>
                            </div>
                        </div>
                        <button type="button" class="text-red-600 deleteItemBtn" data-target="dynamic_item-{{$rand}}-{{$loop->index}}" @dblclick="removeField">
                            &times;
                        </button>
                    </div>
                    <div class="w-full">
                        <x-lareon::inputs.label :title="__('content') . ' #'. ($loop->iteration)" for="dynamic_item_content-{{$rand}}-{{$loop->index}}"/>
                        <x-lareon::inputs.textarea name="{{$name}}[data][items][{{$loop->index}}][content]" id="dynamic_item_content-{{$rand}}-{{$loop->index}}" class="block w-full">{{$item['content'] ?? ''}}</x-lareon::inputs.textarea>
                    </div>
                    <div class="w-full md:grid-cols-2 gap-3 grid">
                        <div class="w-full">
                            <x-lareon::inputs.label :title="__('link title') . ' #'. ($loop->iteration)" for="dynamic_item_link_title-{{$rand}}-{{$loop->index}}"/>
                            <x-lareon::inputs.text name="{{$name}}[data][items][{{$loop->index}}][link_title]" id="dynamic_item_link_title-{{$rand}}-{{$loop->index}}" class="block w-full" :value="$item['link_title'] ?? ''"/>
                        </div>
                        <div class="w-full">
                            <x-lareon::inputs.label :title="__('link url') . ' #'. ($loop->iteration)" for="dynamic_item_link_url-{{$rand}}-{{$loop->index}}"/>
                            <x-lareon::inputs.text name="{{$name}}[data][items][{{$loop->index}}][link_url]" id="dynamic_item_link_url-{{$rand}}-{{$loop->index}}" class="block w-full" :value="$item['link_url'] ?? ''" dir="ltr"/>
                        </div>
                    </div>
                    <x-lareon::inputs.error :messages="get_error($errors , $name.'['.$loop->index.']')" class="my-2"/>
                </div>
            @endforeach

            <div x-data="function handler(){return { fields: [], addNewField(){this.fields.push({ txt1: '' ,txt2:'', txt3:'' ,txt4:'' ,txt5:''});},removeField(index){ this.fields.splice(index, 1);}}}">
                <div class="mt-6">
                    <template x-data="{'lngth' : document.querySelectorAll('.dynamicGroup_{{$randomItem}}').length}" x-for="(field, index) in fields" :key="index">
                        <div class="dynamicGroup border border-zinc-300 p-3 mb-3" x-bind:id="`dynamicGroup_${index + lngth + 1}`">
                            <div class="my-3 flex justify-between items-center gap-6">
                                <div class="grid gap-3 md:grid-cols-2 w-full">
                                    <div class="w-full">
                                        <label x-text:="`{{__('title')}} #${index + lngth + 1}`" x-bind:for="`dynamic_new_item_title-${index + lngth + 1}`" class="block font-medium text-xs text-gray-600  mb-2">{{__('new :title',['title'=>__('title')])}}</label>
                                        <x-lareon::inputs.text x-bind:id="`dynamic_new_item_title-${index + lngth + 1}`" class="block w-full" x-model="field.txt1" x-bind:name="`{{$name}}[data][items][${index + lngth + 1}][title]`"/>
                                    </div>
                                    <div class="w-full">
                                        <label x-text:="`{{__('image')}} #${index + lngth + 1}`" x-bind:for="`dynamic_new_item_image-${index + lngth + 1}`" class="block font-medium text-xs text-gray-600  mb-2">{{__('new :title',['title'=>__('image')])}}</label>
                                        <x-lareon::inputs.text x-bind:id="`dynamic_new_item_image-${index + lngth + 1}`" class="block w-full" x-model="field.txt2" x-bind:name="`{{$name}}[data][items][${index + lngth + 1}][image]`" dir="ltr"/>
                                    </div>
                                </div>
                                <div class="min-w-fit w-fit">
                                    <button type="button" class="text-red-900" @click="removeField(index)">
                                        &times;
                                    </button>
                                </div>
                            </div>
                            <div>
                                <div class="w-full">
                                    <label x-text:="`{{__('content')}} #${index + lngth + 1}`" x-bind:for="`dynamic_new_item_content-${index + lngth + 1}`" class="block font-medium text-xs text-gray-600  mb-2">{{__('new :title',['title'=>__('content')])}}</label>
                                    <x-lareon::inputs.textarea x-bind:id="`dynamic_new_item_content-${index + lngth + 1}`" class="block w-full" x-model="field.txt3" x-bind:name="`{{$name}}[data][items][${index + lngth + 1}][content]`"></x-lareon::inputs.textarea>
                                </div>
                            </div>
                            <div class="grid gap-3 md:grid-cols-2 w-full">
                                <div class="w-full">
                                    <label x-text:="`{{__('link title')}} #${index + lngth + 1}`" x-bind:for="`dynamic_new_item_link_title-${index + lngth + 1}`" class="block font-medium text-xs text-gray-600  mb-2">{{__('new :title',['title'=>__('link_title')])}}</label>
                                    <x-lareon::inputs.text x-bind:id="`dynamic_new_item_link_title-${index + lngth + 1}`" class="block w-full" x-model="field.txt4" x-bind:name="`{{$name}}[data][items][${index + lngth + 1}][link_title]`"/>
                                </div>
                                <div class="w-full">
                                    <label x-text:="`{{__('link url')}} #${index + lngth + 1}`" x-bind:for="`dynamic_new_item_link_url-${index + lngth + 1}`" class="block font-medium text-xs text-gray-600  mb-2">{{__('new :title',['title'=>__('link')])}}</label>
                                    <x-lareon::inputs.text x-bind:id="`dynamic_new_item_link_url-${index + lngth + 1}`" class="block w-full" x-model="field.txt5" x-bind:name="`{{$name}}[data][items][${index + lngth + 1}][link_url]`" dir="ltr"/>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div class="my-3">
                        <x-lareon::buttons.simple type="button" role="button" title="{{__('add title')}}" id="addDynamic_{{$randomItem}}" @click="addNewField()">
                            {{__('add')}}
                        </x-lareon::buttons.simple>

                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</x-lareon::accordion.single>

