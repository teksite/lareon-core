@props(['name','value' => [],'required' => true, 'arrayName'=>'offers' ,'title'=>__('offers')])

@php
    $finalName = $name."[".$arrayName."]";
    $dottedName = str_replace(['[', ']'], ['.', ''], $finalName);
    $rawItems = old($dottedName, null) ?? $value ?? [];

    $initialItems = collect($rawItems)->map(fn ($item) =>is_array($item)
       ? ['name'=>$item['name'] ?? '','price'=>$item['price'] ?? '','priceCurrency'=>$item['priceCurrency'] ?? '','validFrom'=>$item['validFrom'] ?? '','url'=>$item['url'] ?? '','availability'=>$item['availability'] ?? '',]
       : ['name'=>'','price'=>'','priceCurrency'=>'','validFrom'=>'','url'=>'','availability'=>'',]
)->values();
@endphp

<fieldset class="fieldset"
          x-data="{
        items: {{ $initialItems->toJson() }},
        errors: @js($errors->getMessages()),
        addItem() { this.items.push({ name: '',price: '',priceCurrency: '',validFrom: '',url: '',availability: '',}); },
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
            <div class="space-y-6">

             <div class="flex items-center gap-3 ">
                 <div class="grid gap-6 md:grid-cols-3 w-full">

                     {{-- Name --}}
                     <div class="w-full flex flex-col gap-1">
                         <label class="input_label" :for="`offer_name-${index}`" x-text="`{{ __('name') }} #${index + 1}`"></label>
                         <input type="text" @required($required) class="input block w-full"
                                :class="{ 'input-error': hasError('{{ $dottedName }}.' + index + '.name') }"
                                :name="`{{ $finalName }}[${index}][name]`"
                                :id="`offer_name-${index}`"
                                x-model="item.name">
                         <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.name')" x-text="getError('{{ $dottedName }}.' + index + '.name')"></p>
                     </div>

                     {{-- URL --}}
                     <div class="w-full flex flex-col gap-1">
                         <label class="input_label" :for="`offer_url-${index}`" x-text="`{{ __('url') }} #${index + 1}`"></label>

                         <input type="text" dir="ltr" @required($required) class="input block w-full" placeholder="https://example.com/product | example.com/product "
                                :class="{ 'input-error': hasError('{{ $dottedName }}.' + index + '.url')}"
                                :name="`{{ $finalName }}[${index}][url]`"
                                :id="`offer_url-${index}`"
                                x-model="item.url">

                         <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.url')" x-text="getError('{{ $dottedName }}.' + index + '.url')"></p>
                     </div>

                     {{-- Availability --}}
                     <div class="w-full flex flex-col gap-1">
                         <label class="input_label" :for="`offer_availability-${index}`" x-text="`{{ __('availability') }} #${index + 1}`"></label>

                         <select @required($required) class="input block w-full"
                                 :class="{ 'input-error': hasError('{{ $dottedName }}.' + index + '.availability') }"
                                :name="`{{ $finalName }}[${index}][availability]`"
                                :id="`offer_availability-${index}`"
                                x-model="item.availability">
                             @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('availability_type') as $key=>$desc)
                                 <option value="{{$key}}">{{__($desc)}}</option>
                             @endforeach
                         </select>
                         <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.availability')" x-text="getError('{{ $dottedName }}.' + index + '.availability')"></p>
                     </div>
                 </div>

                 <x-lareon::buttons.simple class="min-w-fit w-fit" size="2xs" color="red" variant="outline" type="button" role="button" title="{{ __('double click to delete') }}" @dblclick="removeItem(index)">
                     &times;
                 </x-lareon::buttons.simple>
             </div>


                <div class="grid gap-6 md:grid-cols-3">

                    {{-- Price --}}
                    <div class="w-full flex flex-col gap-1">
                        <label class="input_label" :for="`offer_price-${index}`" x-text="`{{ __('price') }} #${index + 1}`"></label>

                        <input type="number" min="0" step="any" @required($required) class="input block w-full"
                               :class="{ 'input-error': hasError('{{ $dottedName }}.' + index + '.price')}"
                               :name="`{{ $finalName }}[${index}][price]`"
                               :id="`offer_price-${index}`"
                               x-model="item.price">
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.price')" x-text="getError('{{ $dottedName }}.' + index + '.price')"></p>
                    </div>

                    {{-- Price Currency --}}
                    <div class="w-full flex flex-col gap-1">
                        <label class="input_label" :for="`offer_priceCurrency-${index}`" x-text="`{{ __('price currency') }} #${index + 1}`"></label>
                        <select @required($required) class="input block w-full"
                                :class="{ 'input-error': hasError('{{ $dottedName }}.' + index + '.priceCurrency') }"
                                :name="`{{ $finalName }}[${index}][priceCurrency]`"
                                :id="`offer_priceCurrency-${index}`"
                                x-model="item.priceCurrency">
                            @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('currency_list') as $key=>$desc)
                                <option value="{{$key}}">{{__($desc)}}</option>
                            @endforeach
                        </select>

                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.priceCurrency')" x-text="getError('{{ $dottedName }}.' + index + '.priceCurrency')"></p>
                    </div>

                    {{-- Valid From --}}

                    <div class="w-full flex flex-col gap-1">
                        <label class="input_label" :for="`offer_validFrom-${index}`" x-text="`{{ __('valid from') }} #${index + 1}`"></label>

                        <input type="datetime-local" @required($required) class="input block w-full"
                               :class="{'input-error': hasError('{{ $dottedName }}.' + index + '.validFrom') }"
                               :name="`{{ $finalName }}[${index}][validFrom]`"
                               :id="`offer_validFrom-${index}`"
                               x-model="item.validFrom">

                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.validFrom')" x-text="getError('{{ $dottedName }}.' + index + '.validFrom')"></p>
                    </div>


                </div>


            </div>
        </template>
        <x-lareon::buttons.simple size="xs" color="blue" variant="outline" type="button" role="button" @click="addItem()">
            + {{ __('add') }}
        </x-lareon::buttons.simple>
    </div>
</fieldset>
