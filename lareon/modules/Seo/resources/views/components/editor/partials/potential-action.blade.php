@props(['name','value' => [],'required' => true,])

@php
    $finalName = $name . '[potentialAction]';
    $dottedName = str_replace(['[', ']'], ['.', ''], $finalName);
    $rawItems = old($dottedName, null) ?? $value ?? [];

    $initialItems = collect($rawItems)->map(fn ($item) =>
       is_array($item)
       ? ['type' => $item['type'] ?? '', 'url'=> $item['url'] ?? '',]
       : ['type' => '',    'url'  => ''])->values();
@endphp

<fieldset class="fieldset"
          x-data="{
        items: {{ $initialItems->toJson() }},
        errors: @js($errors->getMessages()),
        addItem() { this.items.push({ type: '', url: '',}); },
        removeItem(index) { this.items.splice(index, 1); },
        hasError(key) { return this.errors[key] !== undefined; },
        getError(key) { return this.errors[key]?.[0] ?? ''; }
    }"
>
    <legend class="legend">{{ __('potential actions') }}</legend>

    @error($dottedName)
    <p class="mb-4 message-error">{{ $message }}</p>
    @enderror

    <div class="space-y-6">

        <template x-for="(item, index) in items" :key="index">
            <div class="mb-6 flex items-center justify-between gap-6">
                <div class="w-full grid gap-6 md:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <label class="input_label" :for="`potential_action_type-${index}`" x-text="`{{ __('type') }} #${index + 1}`"></label>

                        <select class="input block w-full" @required($required)
                        :class="{'input-error': hasError('{{ $dottedName }}.' + index + '.type')}"
                                :name="`{{ $finalName }}[${index}][type]`"
                                :id="`potential_action_type-${index}`"
                                x-model="item.type">
                            @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('potential_action_list') as $key => $title)
                                <option value="{{ $key }}">{{ __($title) }}</option>
                            @endforeach
                        </select>
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.type')" x-text="getError('{{ $dottedName }}.' + index + '.type')"></p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="input_label" :for="`potential_action_url-${index}`" x-text="`{{ __('url') }} #${index + 1}`"></label>

                        <input type="text" dir="ltr" @required($required) class="input block w-full" placeholder="https://example.com | /url"
                               :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.url')}"
                               :name="`{{ $finalName }}[${index}][url]`"
                               :id="`potential_action_url-${index}`"
                               x-model="item.url">
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.url')" x-text="getError('{{ $dottedName }}.' + index + '.url')"></p>
                    </div>
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
