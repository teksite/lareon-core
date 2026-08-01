@props(['rules','value'=>[], 'required'=>true ,'open'=>true ,'accordion'=>false] )
@php($randomItem=rand(100,9999).\Illuminate\Support\Str::random(6).rand(100,9999))
<fieldset class="fieldset">
    <legend class="legend">
        {{__('rules')}}
    </legend>
    <div>
        @foreach($value ?? [] as $key=>$item)
        @php($rand=\Illuminate\Support\Str::random(6).rand(100,9999))
        <div class="dynamicGroup_{{$randomItem}}" id="{{$rand}}-{{$loop->index}}"
             x-data="{ removeField() { this.$el.parentElement.remove(); }}">
            <div class=" mb-3 flex justify-between items-center gap-6">
                <div class="w-full">
                    <x-lareon::input.label :title="__('field') . ' #'. ($loop->iteration)" for="dynamic_field-{{$rand}}-{{$loop->index}}"/>
                    <x-lareon::input.text  name="rules[{{$loop->index}}][field]" id="dynamic_field-{{$rand}}-{{$loop->index}}" class="block w-full" :value="$item['field'] ?? ''"/>
                </div>
                <div class="w-full">
                    <x-lareon::input.label :title="__('rules') . ' #'. ($loop->iteration)" for="dynamic_rules-{{$rand}}-{{$loop->index}}"/>
                    <x-lareon::input.text  name="rules[{{$loop->index}}][rules]" id="dynamic_rules-{{$rand}}-{{$loop->index}}" class="block w-full" :value="$item['rules'] ?? ''" dir="ltr"/>
                </div>
                <button type="button" class="text-red-600 deleteItemBtn" data-target="dynamic_item-{{$rand}}-{{$loop->index}}" @dblclick="removeField">
                    &times;
                </button>
            </div>
            <x-lareon::input.error :messages="$errors->get('rules')" class="my-2"/>
            <x-lareon::input.error :messages="$errors->get('rules.*')" class="my-2"/>
            <x-lareon::input.error :messages="get_error($errors , 'rules['.$loop->index.'][field]')" class="my-2"/>
            <x-lareon::input.error :messages="get_error($errors , 'rules['.$loop->index.'][rules]')" class="my-2"/>
        </div>
        @endforeach

        <div x-data="function handler(){return { fields: [], addNewField(){this.fields.push({ txt1: '', txt2: ''});},removeField(index){ this.fields.splice(index, 1);}}}">
            <div>
                <template x-data="{'lngth' : document.querySelectorAll('.dynamicGroup_{{$randomItem}}').length}" x-for="(field, index) in fields" :key="index">
                    <div class="dynamicGroup" x-bind:id="`dynamicGroup_${index + lngth + 1}`">
                        <div class="my-3 flex justify-between items-center gap-6">
                            <div class="w-full">
                                <label x-text:="`{{__('field')}} #${index + lngth + 1}`" x-bind:for="`dynamic_new_item-${index + lngth + 1}`" class="block font-medium text-xs text-gray-600  mb-2">{{__('new :title',['title'=>__('field')])}}</label>
                                <x-lareon::input.text  x-bind:id="`dynamic_new_item-${index + lngth + 1}`"
                                                       class="block w-full" x-model="field.txt1"
                                                       x-bind:name="`rules[${index + lngth + 1}][field]`"/>
                            </div>
                            <div class="w-full">
                                <label x-text:="`{{__('rules')}} #${index + lngth + 1}`" x-bind:for="`dynamic_new_item-${index + lngth + 1}`" class="block font-medium text-xs text-gray-600  mb-2">{{__('new :title',['title'=>__('rules')])}}</label>
                                <x-lareon::input.text  x-bind:id="`dynamic_new_item-${index + lngth + 1}`"
                                                       class="block w-full" x-model="field.txt2"
                                                       x-bind:name="`rules[${index + lngth + 1}][rules]`" dir="ltr"/>
                            </div>
                            <div>
                                <button type="button" class="text-red-900" @click="removeField(index)">
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <div class="my-3">
                    <x-lareon::button.solid type="button" role="button" title="{{__('add title')}}" id="addDynamic_{{$randomItem}}"
                                            @click="addNewField()">
                        {{__('add')}}
                    </x-lareon::button.solid>

                </div>
            </div>
        </div>
    </div>
</fieldset>

