@props(['data'=>[] ])
@php
    $finalName = 'seo[sitemap]';
    $dottedName = str_replace(['[', ']'], ['.', ''], $finalName);

    $rawImageItems = old("$dottedName.image", $data['image'] ?? [])  ?? [];
    $initialImageItems = collect($rawImageItems)->map(fn ($item) =>is_array($item)
       ? ['image'=> $item['image'] ?? '',]
       : ['image'  => $item,])->values();

    $rawVideoItems = old("$dottedName.video", $data['video'] ?? [])  ?? [];
    $initialVideoItems = collect($rawVideoItems)->map(fn ($item) =>is_array($item)
       ? ['video'=> $item['video'] ?? '',]
       : ['video'  => $item,])->values();
@endphp
<section>
    <div class="bg-slate-50 p-6 bordering rounded-lg space-y-6">
        <x-lareon::editor.input type="number" min="0.1" max="0.9" step="0.1" :required="false" labelPosition="start" :label="__('priority')" name="{{$finalName}}[priority]" :value="$data['priority'] ?? 0.5" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('priority') , 'item'=>__('sitemap')])"/>
        <x-lareon::editor.input-select :required="false" labelPosition="start" :label="__('change frequency')" name="{{$finalName}}[change_frequency]">
            @foreach(\Lareon\Modules\Seo\App\Enums\ChangeFrequencyEnum::cases() as $case)
                <option value="{{$case->value}}">{{__($case->name)}}</option>
            @endforeach
        </x-lareon::editor.input-select>


        <fieldset class="space-y-6 fieldset" x-data="{
        items: {{ $initialImageItems->toJson() }},
        errors: @js($errors->getMessages()),
        addItem() { this.items.push({ image: '',}); },
        removeItem(index) { this.items.splice(index, 1); },
        hasError(key) { return this.errors[key] !== undefined; },
        getError(key) { return this.errors[key]?.[0] ?? ''; }
    }">
            <legend>{{__('image')}}</legend>
            <template x-for="(item, index) in items" :key="index">
                <div class="mb-6 flex items-center justify-between gap-6">
                    <div class="w-full flex flex-col gap-1">
                        <label class="input_label" :for="`sitemap_image_url-${index}`" x-text="`{{ __('url') }} #${index + 1}`"></label>
                        <input type="text" dir="ltr" required class="input block w-full" placeholder="https://example.com"
                               :class="{'input-error':hasError('{{ $dottedName }}.image.' + index)}"
                               :name="`{{ $finalName }}[image][${index}]`"
                               :id="`sitemap_image_url-${index}`"
                               x-model="item.image">
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.image.' + index )" x-text="getError('{{ $dottedName }}.image.' + index)"></p>
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
        addItem() { this.items.push({ video: '',}); },
        removeItem(index) { this.items.splice(index, 1); },
        hasError(key) { return this.errors[key] !== undefined; },
        getError(key) { return this.errors[key]?.[0] ?? ''; }
    }">
            <legend>{{__('video')}}</legend>
            <template x-for="(item, index) in items" :key="index">
                <div class="mb-6 flex items-center justify-between gap-6">
                    <div class="w-full flex flex-col gap-1">
                        <label class="input_label" :for="`sitemap_video_url-${index}`" x-text="`{{ __('url') }} #${index + 1}`"></label>
                        <input type="text" dir="ltr" required class="input block w-full" placeholder="https://example.com"
                               :class="{'input-error':hasError('{{ $dottedName }}.video.' + index)}"
                               :name="`{{ $finalName }}[video][${index}]`"
                               :id="`sitemap_video_url-${index}`"
                               x-model="item.video">
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.video.' + index )" x-text="getError('{{ $dottedName }}.video.' + index)"></p>
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
