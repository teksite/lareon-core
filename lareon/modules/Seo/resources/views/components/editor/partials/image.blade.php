@props(['name','value' => [],'required' => true, 'arrayName'=>'image' ,'title'=>__('image')])

@php
    $finalName = $name."[".$arrayName."]";

    $dottedName = str_replace(['[', ']'],    ['.', ''],    $finalName);
    $rawItems = old($dottedName, $value ?? []);

    $initialItems = collect($rawItems)
        ->filter(fn ($item) => is_string($item) && trim($item) !== '')
        ->values()
        ->toArray();
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
<div
    class="space-y-6"
    x-data="{
        items: @js($initialItems),
        errors: @js($errors->getMessages()),
        addItem() { this.items.push('');},
        removeItem(index) { this.items.splice(index, 1);},
        hasError(key) { return Object.prototype.hasOwnProperty.call(this.errors, key); },
        getError(key) { return this.errors[key]?.[0] ?? '';}
    }"
>
    @error($dottedName)
    <p class="mb-4 message-error">
        {{ $message }}
    </p>
    @enderror

    <template x-for="(item, index) in items" :key="index">
        <div class="mb-6 flex items-center justify-between gap-6">
            <div class="w-full flex flex-col gap-1">
                <label class="input_label" :for="`image_url-${index}`" x-text="`{{ __('url') }} #${index + 1}`"></label>

                <input type="text" dir="ltr" @required($required) class="input block w-full" placeholder="https://example.com"
                       :class="{'input-error': hasError('{{ $dottedName }}.' + index )}"
                       :name="`{{ $finalName }}[${index}]`"
                       :id="`image_url-${index}`"
                       x-model="items[index]">
                <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index)" x-text="getError('{{ $dottedName }}.' + index)"></p>
            </div>
            <x-lareon::buttons.simple size="2xs" color="red" variant="outline" type="button" role="button" title="{{ __('double click to delete') }}" @dblclick="removeItem(index)">
                &times;
            </x-lareon::buttons.simple>
        </div>
    </template>
    <x-lareon::buttons.simple size="xs" color="blue" variant="outline" type="button" role="button" @click="addItem()">
        + {{ __('add') }}
    </x-lareon::buttons.simple>
</div>
</fieldset>
