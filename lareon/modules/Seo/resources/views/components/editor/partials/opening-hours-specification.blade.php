@props(['name','value' => [],'required' => true, 'arrayName'=>'openingHoursSpecification'])

@php

    $finalName = $name."[".$arrayName."]";
    $dottedName = str_replace(['[', ']'], ['.', ''], $finalName);
    $rawItems = old($dottedName, null) ?? $value ?? [];

    $initialItems = collect($rawItems)->map(fn ($item) =>is_array($item)
       ? ['dayOfWeek' => $item['dayOfWeek'] ?? '', 'opens'=> $item['opens'] ?? '','closes'=> $item['closes'] ?? '',]
       : ['dayOfWeek' => '', 'opens'  => '','closes'  => '',])->values();
@endphp

<fieldset class="fieldset"
          x-data="{
        items: {{ $initialItems->toJson() }},
        errors: @js($errors->getMessages()),
        addItem() { this.items.push({ dayOfWeek: '', opens: '',closes: ''}); },
        removeItem(index) { this.items.splice(index, 1); },
        hasError(key) { return this.errors[key] !== undefined; },
        getError(key) { return this.errors[key]?.[0] ?? ''; }
    }"
>
    <legend class="legend">{{ __('opening hours specification') }}</legend>

    @error($dottedName)
        <p class="mb-4 message-error">{{ $message }}</p>
    @enderror

    <div class="space-y-6">
        <template x-for="(item, index) in items" :key="index">
            <div class="mb-6 flex items-center justify-between gap-6">
                <div class="w-full grid gap-6 md:grid-cols-2">
                    <div class="flex flex-col gap-1">
                        <label class="input_label" :for="`opening_horse_day_of_week-${index}`" x-text="`{{ __('day of week') }} #${index + 1}`"></label>

                        <select class="input block w-full" @required($required)
                        :class="{'input-error': hasError('{{ $dottedName }}.' + index + '.dayOfWeek')}"
                                :name="`{{ $finalName }}[${index}][dayOfWeek]`"
                                :id="`opening_horse_day_of_week-${index}`"
                                x-model="item.dayOfWeek">
                            @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('day_list') as $key => $title)
                                <option value="{{ $key }}">{{ __($title) }}</option>
                            @endforeach
                        </select>
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.type')" x-text="getError('{{ $dottedName }}.' + index + '.dayOfWeek')"></p>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex flex-col gap-1 w-full">
                            <label class="input_label" :for="`opening_horse_opens-${index}`" x-text="`{{ __('opens') }} #${index + 1}`"></label>
                            <input type="time" dir="ltr" @required($required) class="input block w-full"
                                   :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.opens')}"
                                   :name="`{{ $finalName }}[${index}][opens]`"
                                   :id="`opening_horse_opens-${index}`"
                                   x-model="item.opens">
                            <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.opens')" x-text="getError('{{ $dottedName }}.' + index + '.opens')"></p>
                        </div>
                        <div class="flex flex-col gap-1 w-full">
                            <label class="input_label" :for="`opening_horse_closes-${index}`" x-text="`{{ __('closes') }} #${index + 1}`"></label>
                            <input type="time" dir="ltr" @required($required) class="input block w-full"
                                   :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.closes')}"
                                   :name="`{{ $finalName }}[${index}][closes]`"
                                   :id="`opening_horse_closes-${index}`"
                                   x-model="item.closes">
                            <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.closes')" x-text="getError('{{ $dottedName }}.' + index + '.closes')"></p>
                        </div>
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
