@props(['rules','value'=>[], 'required'=>true ,'open'=>true ,'accordion'=>false] )
@php($randomItem=rand(100,9999).\Illuminate\Support\Str::random(6).rand(100,9999))
<div>
    @foreach($value['args']['items'] ?? [] as $key=>$item)
        @php($rand=\Illuminate\Support\Str::random(6).rand(100,9999))
        <div class="dynamicGroup_{{$randomItem}}" id="{{$rand}}-{{$loop->index}}" x-data="{ removeField() { this.$el.parentElement.remove(); }}">
            <div class=" mb-3 flex justify-between items-center gap-6">
                <div class="w-full">
                    <x-lareon::inputs.label :title="__('argument') . ' #'. ($loop->iteration)" for="dynamic_field-{{$rand}}-{{$loop->index}}"/>
                    <x-lareon::inputs.text name="settings[args][items][{{$loop->index}}]" id="dynamic_field-{{$rand}}-{{$loop->index}}" class="block w-full" :value="$item?? ''"/>
                </div>
                <button type="button" class="text-red-600 deleteItemBtn" data-target="dynamic_item-{{$rand}}-{{$loop->index}}" @dblclick="removeField">
                    &times;
                </button>
            </div>
            <x-lareon::inputs.error :messages="$errors->get('settings.args')" class="my-2"/>
            <x-lareon::inputs.error :messages="$errors->get('settings.args.items')" class="my-2"/>
            <x-lareon::inputs.error :messages="$errors->get('settings.args.items.{{$loop->index}}')" class="my-2"/>
        </div>
    @endforeach

    <div x-data="function handler(){return { fields: [], addNewField(){this.fields.push({ txt1: '', txt2: ''});},removeField(index){ this.fields.splice(index, 1);}}}">
        <div>
            <template x-data="{'lngth' : document.querySelectorAll('.dynamicGroup_{{$randomItem}}').length}" x-for="(field, index) in fields" :key="index">
                <div class="dynamicGroup" x-bind:id="`dynamicGroup_${index + lngth + 1}`">
                    <div class="my-3 flex justify-between items-center gap-6">
                        <div class="w-full">

                            <label x-text:="`{{__('argument')}} #${index + lngth + 1}`" x-bind:for="`dynamic_new_item-${index + lngth + 1}`" class="block font-medium text-xs text-gray-600  mb-2">{{__('new :title',['title'=>__('argument')])}}</label>
                            <x-lareon::inputs.text x-bind:id="`dynamic_new_item-${index + lngth + 1}`"
                                                   class="block w-full" x-model="field.txt1"
                                                   :required="true"
                                                   x-bind:name="`settings[args][items][${index + lngth + 1}]`"/>
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

                <x-lareon::buttons.simple variant="outline" type="button" role="button" title="{{__('add')}}" id="addDynamic_{{$randomItem}}" @click="addNewField()">
                    {{__('add')}}
                </x-lareon::buttons.simple>
            </div>
        </div>
    </div>
</div>


