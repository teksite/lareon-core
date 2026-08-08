@props(['data'=>[] ])
@php
    $finalName = 'seo[sitemap]';
    $dottedName = str_replace(['[', ']'], ['.', ''], $finalName);

    $rawImageItems = old("$dottedName.images", $data['image'] ?? [])  ?? [];
    $initialImageItems = collect($rawImageItems)->map(fn ($item) =>is_array($item)
       ? ['url'=> $item['url'] ?? '',]
       : ['url'  => $item,])->values();


    $rawVideoItems = old("$dottedName.videos", $data['video'] ?? [])  ?? [];
    $initialVideoItems = collect($rawVideoItems)->map(fn ($item) =>is_array($item)
       ? ['url'=> $item['url'] ?? '',]
       : ['url'  => $item,])->values();
@endphp
<section>
    <div class="bg-slate-50 p-6 bordering rounded-lg space-y-6">

        <x-lareon::editor.input type="number" min="0.1" max="0.9" step="0.1" :required="false" labelPosition="start" :label="__('priority')" name="{{$finalName}}[priority]" :value="$data['priority'] ?? 0.5" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('priority') , 'item'=>__('sitemap')])"/>

        <x-lareon::editor.input-select :required="false" labelPosition="start" :label="__('change frequency')" name="{{$finalName}}[change_frequency]">
            @foreach(\Lareon\Modules\Seo\App\Enums\ChangeFrequencyEnum::cases() as $case)
                <option value="{{$case->name}}">{{__($case->value)}}</option>
            @endforeach
        </x-lareon::editor.input-select>


        <fieldset class="space-y-6 fieldset" x-data="{
        items: {{ $initialImageItems->toJson() }},
        errors: @js($errors->getMessages()),
        addItem() { this.items.push({ url: '',}); },
        removeItem(index) { this.items.splice(index, 1); },
        hasError(key) { return this.errors[key] !== undefined; },
        getError(key) { return this.errors[key]?.[0] ?? ''; }
    }">
            <legend>{{__('image')}}</legend>

            <template x-for="(item, index) in items" :key="index">
                <div class="mb-6 flex items-center justify-between gap-6">
                    <div class="w-full flex flex-col gap-1">
                        <label class="input_label" :for="`sitemap_images_url-${index}`" x-text="`{{ __('url') }} #${index + 1}`"></label>
                        <input type="text" dir="ltr" required class="input block w-full" placeholder="https://example.com"
                               :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.url')}"
                               :name="`{{ $finalName }}[${index}][images]`"
                               :id="`sitemap_images_url-${index}`"
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
        </fieldset>


        <fieldset class="space-y-6 fieldset" x-data="{
        items: {{ $initialVideoItems->toJson() }},
        errors: @js($errors->getMessages()),
        addItem() { this.items.push({ url: '',}); },
        removeItem(index) { this.items.splice(index, 1); },
        hasError(key) { return this.errors[key] !== undefined; },
        getError(key) { return this.errors[key]?.[0] ?? ''; }
    }">
            <legend>{{__('video')}}</legend>
            <template x-for="(item, index) in items" :key="index">
                <div class="mb-6 flex items-center justify-between gap-6">
                    <div class="w-full flex flex-col gap-1">
                        <label class="input_label" :for="`sitemap_videos_url-${index}`" x-text="`{{ __('url') }} #${index + 1}`"></label>
                        <input type="text" dir="ltr" required class="input block w-full" placeholder="https://example.com"
                               :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.url')}"
                               :name="`{{ $finalName }}[${index}][videos]`"
                               :id="`sitemap_videos_url-${index}`"
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
        </fieldset>
    </div>
</section>
