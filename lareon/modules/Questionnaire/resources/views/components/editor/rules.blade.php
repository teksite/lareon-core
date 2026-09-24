@props(['value' => [],'required' => false ])

@php
    $finalName = 'rules';

    $dottedName = str_replace(['[', ']'], ['.', ''], $finalName);
    $rawItems = old($dottedName, $value ?? []) ?? [] ;

    $initialItems = collect($rawItems)->map(function($item){
         return [
                'field' => is_array($item) ? ($item['field'] ?? '') : '',
                'rules' => is_array($item) ? ($item['rules'] ?? ''): '',
            ];
        })
        ->values();

@endphp

<fieldset class="fieldset"
          x-data="{
        rules: {{ $initialItems->toJson() }},
        errors: @js($errors->getMessages()),
        addItem() { this.rules.push({ field: '',rules: '',}); },
        removeItem(index) { this.rules.splice(index, 1); },
        hasError(key) { return this.errors[key] !== undefined; },
        getError(key) { return this.errors[key]?.[0] ?? ''; }
    }"
>
    <legend class="legend">{{__('rules')}}</legend>
    @error($dottedName)
    <p class="mb-4 message-error">{{ $message }}</p>
    @enderror

    <div class="space-y-6">
        <template x-for="(item, index) in rules" :key="index">
                <div class="flex items-center gap-6 hover:bg-gray-50">
                    <div class="w-full flex flex-col gap-1">
                        <label class="input_label" :for="`rule_field-${index}`" x-text="`{{ __('field') }} #${index + 1}`"></label>
                        <input type="text" @required($required) class="input block w-full" placeholder="write your field"
                               :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.field')}"
                               :name="`{{ $finalName }}[${index}][field]`"
                               :id="`rule_field-${index}`"
                               x-model="item.field">
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.field')" x-text="getError('{{ $dottedName }}.' + index + '.field')"></p>
                    </div>
                    <div class="w-full flex flex-col gap-1">
                        <label class="input_label" :for="`rule_rules-${index}`" x-text="`{{ __('rules') }} #${index + 1}`"></label>
                        <input @required($required) class="input block w-full" placeholder="write your rules"
                               :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.rules')}"
                               :name="`{{ $finalName }}[${index}][rules]`"
                               :id="`rule_rules-${index}`"
                               x-model="item.rules">
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.rules')" x-text="getError('{{ $dottedName }}.' + index + '.rules')"></p>
                    </div>
                    <x-lareon::buttons.simple class="min-w-fit w-fit" size="2xs" color="red" variant="outline" type="button" role="button" title="{{ __('double click to delete') }}" @dblclick="removeItem(index)">
                        &times;
                    </x-lareon::buttons.simple>
                </div>
        </template>
        <x-lareon::buttons.simple size="xs" color="blue" variant="outline" type="button" role="button" @click="addItem()">
            + {{ __('add') }}
        </x-lareon::buttons.simple>
    </div>
</fieldset>
