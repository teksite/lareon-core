@props(['name','value'=>[], 'required'=>false, 'arrayName'=>'offer' ,'title'=>__('offer')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">

    <legend class="legend">{{$title}}</legend>
    <x-lareon::editor.input-select :required="false" :label="__('offerType')" name="{{$finalName}}[offerType]" :value="$value['offerType'] ?? ''" labelPosition="top">
        @foreach(['none'=>'' ,'Offer'=>'offer' , 'AggregateOffer'=>'aggregate offer'] as $key=>$item)
            <option value="{{$key}}">
                {{__($item)}}
            </option>
        @endforeach

    </x-lareon::editor.input-select>

    <div class="space-y-6" data-type="AggregateOffer">
        <x-lareon::editor.input :label="__('url')" name="{{$finalName}}[url]" :value="$value['url'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('page')])"/>
        <div class="grid gap-6 md:grid-cols-3">
            <x-lareon::editor.input type="number" dir="ltr" :label="__('price')" name="{{$finalName}}[price]" :value="$value['price'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('page')])"/>
            <x-seo::currency label="__('price currency')" name="{{$finalName}}[priceCurrency]" :value="$value['priceCurrency'] ?? null" :required="false" labelPosition="top"/>
            <x-lareon::editor.input-date type="date" :label="__('price valid until')" name="{{$finalName}}[priceValidUntil]" :value="$value['priceValidUntil'] ?? null" :required="false" labelPosition="top"/>
        </div>
        <div class="grid gap-6 md:grid-cols-2">

            <x-lareon::editor.input-select :required="true" :label="__('availability')" name="{{$finalName}}[availability]" :value="$value['availability'] ?? ''" labelPosition="top">
                <option value="">
                    {{__('none')}}
                </option>
                @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('availability_list') as $key=>$item)
                    <option value="{{$key}}">
                        {{__($item)}}
                    </option>
                @endforeach
            </x-lareon::editor.input-select>

            <x-lareon::editor.input-select :required="true" :label="__('item condition')" name="{{$finalName}}[itemCondition]" :value="$value['itemCondition'] ?? ''" labelPosition="top">
                <option value="">
                    {{__('none')}}
                </option>
                @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('item_condition_list') as $key=>$item)
                    <option value="{{$key}}">
                        {{__($item)}}
                    </option>
                @endforeach
            </x-lareon::editor.input-select>
        </div>
    </div>


    <div class="space-y-6" data-type="offer">
        <x-lareon::editor.input :label="__('url')" name="{{$finalName}}[url]" :value="$value['url'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('page')])"/>
        <div class="grid gap-6 md:grid-cols-3">
            <x-lareon::editor.input type="number" dir="ltr" :label="__('low price')" name="{{$finalName}}[lowPrice]" :value="$value['lowPrice'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('page')])"/>
            <x-lareon::editor.input type="number" dir="ltr" :label="__('high price')" name="{{$finalName}}[highPrice]" :value="$value['highPrice'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('page')])"/>
            <x-seo::currency label="__('price currency')" name="{{$finalName}}[priceCurrency]" :value="$value['priceCurrency'] ?? null" :required="false" labelPosition="top"/>
        </div>
        <x-lareon::editor.input type="number" min="0" dir="ltr" :label="__('offer count')" name="{{$finalName}}[offerCount]" :value="$value['offerCount'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('page')])"/>

    </div>
</fieldset>
