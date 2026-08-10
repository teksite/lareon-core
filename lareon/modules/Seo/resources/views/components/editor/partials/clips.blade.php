@props(['name','value' => [],'required' => true, 'arrayName'=>'clip'])

@php
    $finalName = $name."[".$arrayName."]";
    $dottedName = str_replace(['[', ']'], ['.', ''], $finalName);
    $rawItems = old($dottedName, null) ?? $value ?? [];

    $initialItems = collect($rawItems)->map(fn ($item) =>is_array($item)
       ? ['name'=> $item['name'] ?? '', 'description'=>$item['description'] ?? '', 'startOffset'=>$item['startOffset'] ?? '', 'endOffset'=>$item['endOffset'] ?? '', 'url'=>$item['url'] ?? '']
       :['name'=> '' , 'description'=>'', 'startOffset'=>'', 'endOffset'=>'' , 'url'=>'']
       )->values();
@endphp

<fieldset class="fieldset"
          x-data="{
        items: {{ $initialItems->toJson() }},
        errors: @js($errors->getMessages()),
        addItem() { this.items.push({ name: '', description: '', startOffset: '', endOffset: '', url: ''}); },
        removeItem(index) { this.items.splice(index, 1); },
        hasError(key) { return this.errors[key] !== undefined; },
        getError(key) { return this.errors[key]?.[0] ?? ''; }
    }"
>
    <legend class="legend">{{ __('clips') }}</legend>

    @error($dottedName)
    <p class="mb-4 message-error">{{ $message }}</p>
    @enderror

    <div class="space-y-6">

        <template x-for="(item, index) in items" :key="index">
            <div class="space-y-6">
                <div class=" flex items-center justify-between gap-6" >
                    <div class="grid gap-6 md:grid-cols-2 w-full">
                        <div class="w-full flex flex-col gap-1">
                            <label class="input_label" :for="`clip_name-${index}`" x-text="`{{ __('name') }} #${index + 1}`"></label>
                            <input type="text" @required($required) class="input block w-full" placeholder="https://example.com"
                                   :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.name')}"
                                   :name="`{{ $finalName }}[${index}][name]`"
                                   :id="`clip_name-${index}`"
                                   x-model="item.name">
                            <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.name')" x-text="getError('{{ $dottedName }}.' + index + '.name')"></p>
                        </div>
                        <div class="w-full flex flex-col gap-1">
                            <label class="input_label" :for="`clip_name-${index}`" x-text="`{{ __('url') }} #${index + 1}`"></label>
                            <input type="text" dir="ltr" @required($required) class="input block w-full" placeholder="https://example.com"
                                   :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.url')}"
                                   :name="`{{ $finalName }}[${index}][url]`"
                                   :id="`clip_url-${index}`"
                                   x-model="item.url">
                            <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.url')" x-text="getError('{{ $dottedName }}.' + index + '.url')"></p>
                        </div>
                    </div>
                    <x-lareon::buttons.simple size="2xs" color="red" variant="outline" type="button" role="button" title="{{ __('double click to delete') }}" @dblclick="removeItem(index)">
                        &times;
                    </x-lareon::buttons.simple>
                </div>
                <div>
                    <div class="grid gap-6 md:grid-cols-2 w-full">
                        <div class="w-full flex flex-col gap-1">
                            <label class="input_label" :for="`clip_startOffset-${index}`" x-text="`{{ __('start offset') }} #${index + 1}`"></label>
                            <input type="number" min="0" @required($required) class="input block w-full"
                                   :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.startOffset')}"
                                   :name="`{{ $finalName }}[${index}][startOffset]`"
                                   :id="`clip_startOffset-${index}`"
                                   x-model="item.startOffset">
                            <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.startOffset')" x-text="getError('{{ $dottedName }}.' + index + '.startOffset')"></p>
                        </div>
                        <div class="w-full flex flex-col gap-1">
                            <label class="input_label" :for="`clip_endOffset-${index}`" x-text="`{{ __('end offset') }} #${index + 1}`"></label>
                            <input type="number" min="0" @required($required) class="input block w-full"
                                   :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.endOffset')}"
                                   :name="`{{ $finalName }}[${index}][endOffset]`"
                                   :id="`clip_endOffset-${index}`"
                                   x-model="item.endOffset">
                            <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.endOffset')" x-text="getError('{{ $dottedName }}.' + index + '.endOffset')"></p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="w-full flex flex-col gap-1">
                        <label class="input_label" :for="`clip_description-${index}`" x-text="`{{ __('end offset') }} #${index + 1}`"></label>
                        <textarea type="text" @required($required) class="input block w-full"
                               :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.description')}"
                               :name="`{{ $finalName }}[${index}][description]`"
                               :id="`clip_description-${index}`"
                                  x-model="item.description"></textarea>
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.description')" x-text="getError('{{ $dottedName }}.' + index + '.description')"></p>
                    </div>

                </div>
            </div>

        </template>
        <x-lareon::buttons.simple size="xs" color="blue" variant="outline" type="button" role="button" @click="addItem()">
            + {{ __('add') }}
        </x-lareon::buttons.simple>
    </div>
</fieldset>
