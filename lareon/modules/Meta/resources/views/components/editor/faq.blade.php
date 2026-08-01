@props(['title', 'name','value'=>[], 'placeholder'=>null, 'required'=>false ,'open'=>false ,'accordion'=>false] )
@php($randomItem=rand(100,9999).\Illuminate\Support\Str::random(6).rand(100,9999))
<x-lareon::accordion.box :title="__($title)" :open="$open" :accordion="$accordion">
    <fieldset class="fieldset">
        <legend class="legend">
            {{$title}}
        </legend>
        <div>
            @foreach($value ?? [] as $key=>$item)
                @php($rand=\Illuminate\Support\Str::random(6).rand(100,9999))
                <div class="dynamicGroup_{{$randomItem}} border border-zinc-600 mb-3 rounded-lg p-3" id="content-content-{{$rand}}-{{$loop->index}}" x-data="{ removeField() { document.getElementById('content-content-{{$rand}}-{{$loop->index}}').remove(); }}">
                    <div class=" mb-3 flex justify-between items-center gap-6">
                        <div class="w-full md:grid-cols-2 gap-3 grid">
                            <div class="w-full">
                                <x-lareon::inputs.label :title="__('title') . ' #'. ($loop->iteration)" for="dynamic_item_title-{{$rand}}-{{$loop->index}}"/>
                                <x-lareon::inputs.text  name="{{$name}}[{{$loop->index}}][question]" id="dynamic_item_title-{{$rand}}-{{$loop->index}}" class="block w-full" :value="$item['question'] ?? ''"/>
                            </div>
                        </div>
                        <button type="button" class="text-red-600 deleteItemBtn" data-target="dynamic_item-{{$rand}}-{{$loop->index}}" @dblclick="removeField">
                            &times;
                        </button>
                    </div>
                    <div class="w-full">
                        <x-lareon::inputs.label :title="__('answer') . ' #'. ($loop->iteration)" for="dynamic_item_answer-{{$rand}}-{{$loop->index}}"/>
                        <x-lareon::inputs.textarea  name="{{$name}}[{{$loop->index}}][answer]" id="dynamic_item_answer-{{$rand}}-{{$loop->index}}" class="block w-full" >{{$item['answer'] ?? ''}}</x-lareon::inputs.textarea>
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
                                        <label x-text:="`{{__('question')}} #${index + lngth + 1}`" x-bind:for="`dynamic_new_item_question-${index + lngth + 1}`" class="block font-medium text-xs text-gray-600  mb-2">{{__('new :title',['title'=>__('question')])}}</label>
                                        <x-lareon::inputs.text  x-bind:id="`dynamic_new_item_question-${index + lngth + 1}`" class="block w-full" x-model="field.txt1" x-bind:name="`{{$name}}[${index + lngth + 1}][question]`"/>
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
                                    <label x-text:="`{{__('answer')}} #${index + lngth + 1}`" x-bind:for="`dynamic_new_item_answer-${index + lngth + 1}`" class="block font-medium text-xs text-gray-600  mb-2">{{__('new :title',['title'=>__('answer')])}}</label>
                                    <x-lareon::inputs.textarea  x-bind:id="`dynamic_new_item_answer-${index + lngth + 1}`" class="block w-full" x-model="field.txt3" x-bind:name="`{{$name}}[${index + lngth + 1}][answer]`"></x-lareon::inputs.textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div class="my-3">
                        <x-lareon::button.solid type="button" role="button" title="{{__('add title')}}" id="addDynamic_{{$randomItem}}" @click="addNewField()">
                            {{__('add')}}
                        </x-lareon::button.solid>

                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</x-lareon::accordion.box>

