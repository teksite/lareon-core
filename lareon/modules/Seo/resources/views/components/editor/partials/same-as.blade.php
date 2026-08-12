@props(['name','value' => [],'required' => true, 'arrayName'=>'sameAs' ,'title'=>__('same as')])

@php
    $finalName = $name."[".$arrayName."]";
    $dottedName = str_replace(['[', ']'], ['.', ''], $finalName);
    $rawItems = old($dottedName, null) ?? $value ?? [];

    $initialItems = collect($rawItems)->map(fn ($item) =>is_array($item)
       ? ['url'=> $item['url'] ?? '',]
       : ['url'  => $item,])->values();
@endphp

<fieldset class="fieldset"
          x-data="{
        items: {{ $initialItems->toJson() }},
        errors: @js($errors->getMessages()),
        addItem() { this.items.push({ url: '',}); },
        removeItem(index) { this.items.splice(index, 1); },
        hasError(key) { return this.errors[key] !== undefined; },
        getError(key) { return this.errors[key]?.[0] ?? ''; }
    }"
>
    <legend class="legend">{{$title}}</legend>
    @error($dottedName)
    <p class="mb-4 message-error">{{ $message }}</p>
    @enderror

    <div class="space-y-6">

        <template x-for="(item, index) in items" :key="index">
            <div class="mb-6 flex items-center justify-between gap-6">
                <div class="w-full flex flex-col gap-1">
                    <label class="input_label" :for="`sameass_url-${index}`" x-text="`{{ __('url') }} #${index + 1}`"></label>
                    <input type="text" dir="ltr" @required($required) class="input block w-full" placeholder="https://example.com"
                           :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.url')}"
                           :name="`{{ $finalName }}[${index}][url]`"
                           :id="`sameass_url-${index}`"
                           x-model="item.url">
                    <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.url')" x-text="getError('{{ $dottedName }}.' + index + '.url')"></p>
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
