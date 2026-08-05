@props(['name','value' => [],'required' => true,])

@php
    $contactTypeList       = [
        'customer service'     => 'Customer Service',
        'technical support'    => 'Technical Support',
        'billing support'      => 'Billing Support',
        'bill payment'         => 'Bill Payment',
        'sales'                => 'Sales',
        'reservations'         => 'Reservations',
        'credit card support'  => 'Credit Card Support',
        'emergency'            => 'Emergency',
        'baggage tracking'     => 'Baggage Tracking',
        'roadside assistance'  => 'Roadside Assistance',
        'package tracking'     => 'Package Tracking',
    ];

   $contactOptionList       = [
        'toll free',
        'Hearing Impaired Supported',
    ];

    $finalName = $name . '[ContactPoint]';
    $dottedName = str_replace(['[', ']'], ['.', ''], $finalName);
    $rawItems = old($dottedName, null) ?? $value ?? [];

    $initialItems = collect($rawItems)->map(fn ($item) =>
       is_array($item)
       ? ['telephone'=> $item['telephone'] ?? '', 'email'=> $item['email'] ?? '' , 'contactType' => $item['contactType'] ?? ''
       ,'contactOption' => $item['contactOption'] ?? '','areaServed' => $item['areaServed'] ?? '','availableLanguage' => $item['availableLanguage'] ?? '']
       : [])->values();
@endphp

<fieldset class="fieldset"
          x-data="{
        items: {{ $initialItems->toJson() }},
        errors: @js($errors->getMessages()),
        addItem() { this.items.push({ telephone: '', email: '',contactType: '', contactOption: '',areaServed: '', availableLanguage: '',}); },
        removeItem(index) { this.items.splice(index, 1); },
        hasError(key) { return this.errors[key] !== undefined; },
        getError(key) { return this.errors[key]?.[0] ?? ''; }
    }"
>
    <legend class="legend">{{ __('Contact point') }}</legend>

    @error($dottedName)
    <p class="mb-4 message-error">{{ $message }}</p>
    @enderror

    <div class="space-y-6">

        <template x-for="(item, index) in items" :key="index">
            <div class="mb-6 flex items-center justify-between gap-6">
                <div class="w-full grid gap-6 md:grid-cols-3">

                    <div class="flex flex-col gap-1">
                        <label class="input_label" :for="`contact_point_telephone-${index}`" x-text="`{{ __('telephone') }} #${index + 1}`"></label>

                        <input type="text" dir="ltr" @required($required) class="input block w-full" placeholder="https://example.com | /telephone"
                               :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.telephone')}"
                               :name="`{{ $finalName }}[${index}][telephone]`"
                               :id="`contact_point_telephone-${index}`"
                               x-model="item.telephone">
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.telephone')" x-text="getError('{{ $dottedName }}.' + index + '.telephone')"></p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="input_label" :for="`contact_point_email-${index}`" x-text="`{{ __('email') }} #${index + 1}`"></label>

                        <input type="text" dir="ltr" @required($required) class="input block w-full" placeholder="https://example.com | /email"
                               :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.email')}"
                               :name="`{{ $finalName }}[${index}][email]`"
                               :id="`contact_point_email-${index}`"
                               x-model="item.email">
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.email')" x-text="getError('{{ $dottedName }}.' + index + '.email')"></p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="input_label" :for="`contact_point_contactType-${index}`" x-text="`{{ __('contactType') }} #${index + 1}`"></label>

                        <select class="input block w-full" @required($required) data-inline
                                :class="{'input-error': hasError('{{ $dottedName }}.' + index + '.contactType')}"
                                :name="`{{ $finalName }}[${index}][contactType]`"
                                :id="`contact_point_contactType-${index}`"
                                x-model="item.contactType">
                            @foreach($contactTypeList as $key => $title)
                                <option value="{{ $key }}">{{ $title }}</option>
                            @endforeach
                        </select>
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.contactType')" x-text="getError('{{ $dottedName }}.' + index + '.contactType')"></p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="input_label" :for="`contact_point_contactOption-${index}`" x-text="`{{ __('contactOption') }} #${index + 1}`"></label>

                        <select class="input block w-full" @required($required) data-inline
                                :class="{'input-error': hasError('{{ $dottedName }}.' + index + '.contactOption')}"
                                :name="`{{ $finalName }}[${index}][contactOption]`"
                                :id="`contact_point_contactOption-${index}`"
                                x-model="item.contactOption">
                            @foreach($contactOptionList as $key => $title)
                                <option value="{{ $key }}">{{ $title }}</option>
                            @endforeach
                        </select>
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.contactOption')" x-text="getError('{{ $dottedName }}.' + index + '.contactOption')"></p>
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
