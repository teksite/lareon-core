@props(['title','name','elementId','value' => [],'open' => false,'accordion' => false,])

@php
    $randomItem = rand(100, 9999) . \Illuminate\Support\Str::random(6) . rand(100, 9999);

    $finalItemsName = $name . '[data][items]';
    $dottedItemsName = str_replace(['[', ']'], ['.', ''], $finalItemsName);

    $rawItems = old($dottedItemsName, null) ?? ($value['items'] ?? []);

    $initialItems = collect($rawItems)->map(function ($item) {
            return is_array($item)
                ? [
                    'title' => $item['title'] ?? '',
                    'image' => $item['image'] ?? '',
                    'content' => $item['content'] ?? '',
                    'link_title' => $item['link_title'] ?? '',
                    'link_url' => $item['link_url'] ?? '',
                ]
                : [
                    'title' => '',
                    'image' => '',
                    'content' => '',
                    'link_title' => '',
                    'link_url' => '',
                ];
        })->values();
@endphp

<x-lareon::accordion.single :title="__($title)" :open="$open" :accordion="$accordion">
    <input type="hidden" name="{{ $name }}[element_id]" value="{{ $elementId }}">

    {{-- Main data --}}
    <div>
        <div class="grid gap-3 md:grid-cols-2">
            <div class="w-full">
                <x-lareon::editor.input :required="false" labelPosition="top" :label="__('title')" name="{{ $name }}[data][title]" :value="$value['title']" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('title') ])"/>
            </div>

            <div class="w-full">
                <x-lareon::editor.input :required="false" dir="ltr" labelPosition="top" :label="__('image')" name="{{ $name }}[data][image]" :value="$value['image']" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('image')])"/>
            </div>
        </div>

        <div>
            <x-lareon::editor.input-textarea :required="false" labelPosition="top" :label="__('content')" name="{{ $name }}[data][content]"  :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('content')])">{{$value['content']}}</x-lareon::editor.input-textarea>
        </div>

        <div class="grid gap-3 md:grid-cols-2">
            <div class="w-full">
                <x-lareon::editor.input :required="false" labelPosition="top" :label="__('link title')" name="{{ $name }}[data][link_title]" :value="$value['link_title']" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('link title')])"/>
            </div>

            <div class="w-full">
                <x-lareon::editor.input :required="false" dir="ltr" labelPosition="top" :label="__('link url')" name="{{ $name }}[data][link_url]" :value="$value['link_url']" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('link url')])"/>
            </div>
        </div>
    </div>

    {{-- Dynamic items --}}
    <fieldset
        class="fieldset"
        x-data="{
            items: @js($initialItems),
            errors: @js($errors->getMessages()),

            addItem() {
                this.items.push({
                    title: '',
                    image: '',
                    content: '',
                    link_title: '',
                    link_url: ''
                });
            },

            removeItem(index) {
                this.items.splice(index, 1);
            },
            getErrorKey(index, field) {
                return '{{ $dottedItemsName }}.' + index + '.' + field;
            },
            hasError(index, field) {
                return this.errors[this.getErrorKey(index, field)] !== undefined;
            },
            getError(index, field) {
                return this.errors[this.getErrorKey(index, field)]?.[0] ?? '';
            }
        }"
    >
        <legend class="legend">
            {{ $title }}
        </legend>

        @error($dottedItemsName)
        <p class="mb-4 message-error">
            {{ $message }}
        </p>
        @enderror

        <div class="space-y-6">

            <template x-for="(item, index) in items" :key="index">
                <div class="border border-zinc-600 rounded-lg p-3">
                    {{-- Header --}}
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <div class="font-medium">
                            {{ __('item') }}
                            #<span x-text="index + 1"></span>
                        </div>

                        <x-lareon::buttons.simple size="2xs" color="red" variant="outline" type="button" role="button" title="{{ __('double click to delete') }}" @dblclick="removeItem(index)">
                            &times;
                        </x-lareon::buttons.simple>

                    </div>

                    {{-- Title + Image --}}
                    <div class="grid gap-3 md:grid-cols-2">

                        {{-- Title --}}
                        <div class="w-full flex flex-col gap-1">
                            <label class="input_label" :for="`dynamic_item_title-${index}`" x-text="`{{ __('title') }} #${index + 1}`"></label>
                            <input type="text" class="input block w-full" :class="{'input-error': hasError(index, 'title')}" :name="`{{ $finalItemsName }}[${index}][title]`" :id="`dynamic_item_title-${index}`" x-model="item.title">
                            <p class="message-error" x-show="hasError(index, 'title')" x-text="getError(index, 'title')"></p>
                        </div>

                        {{-- Image --}}
                        <div class="w-full flex flex-col gap-1">
                            <label class="input_label" :for="`dynamic_item_image-${index}`" x-text="`{{ __('image') }} #${index + 1}`"></label>
                            <input type="text" dir="ltr" class="input block w-full" :class="{'input-error': hasError(index, 'image') }" :name="`{{ $finalItemsName }}[${index}][image]`" :id="`dynamic_item_image-${index}`" x-model="item.image">
                            <p class="message-error" x-show="hasError(index, 'image')" x-text="getError(index, 'image')"></p>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="mt-3">

                        <div class="w-full flex flex-col gap-1">
                            <label class="input_label" :for="`dynamic_item_content-${index}`" x-text="`{{ __('content') }} #${index + 1}`"></label>
                            <textarea class="input block w-full" :class="{ 'input-error': hasError(index, 'content') }" :name="`{{ $finalItemsName }}[${index}][content]`" :id="`dynamic_item_content-${index}`" x-model="item.content"></textarea>
                            <p class="message-error" x-show="hasError(index, 'content')" x-text="getError(index, 'content')"></p>
                        </div>
                    </div>

                    {{-- Link --}}
                    <div class="mt-3 grid gap-3 md:grid-cols-2">

                        {{-- Link title --}}
                        <div class="w-full flex flex-col gap-1">

                            <label class="input_label" :for="`dynamic_item_link_title-${index}`" x-text="`{{ __('link title') }} #${index + 1}`"></label>
                            <input type="text" class="input block w-full" :class="{'input-error': hasError(index, 'link_title')  }" :name="`{{ $finalItemsName }}[${index}][link_title]`" :id="`dynamic_item_link_title-${index}`" x-model="item.link_title">
                            <p class="message-error" x-show="hasError(index, 'link_title')" x-text="getError(index, 'link_title')"></p>
                        </div>

                        {{-- Link URL --}}
                        <div class="w-full flex flex-col gap-1">
                            <label class="input_label" :for="`dynamic_item_link_url-${index}`" x-text="`{{ __('link url') }} #${index + 1}`"></label>
                            <input type="text" dir="ltr" class="input block w-full" :class="{ 'input-error': hasError(index, 'link_url') }" :name="`{{ $finalItemsName }}[${index}][link_url]`" :id="`dynamic_item_link_url-${index}`" x-model="item.link_url">
                            <p class="message-error" x-show="hasError(index, 'link_url')" x-text="getError(index, 'link_url')"></p>
                        </div>
                    </div>

                </div>
            </template>

            {{-- Add --}}
            <div class="mt-4">
                <x-lareon::buttons.simple size="xs" color="blue" variant="outline" type="button" role="button" @click="addItem()">
                    + {{ __('add') }}
                </x-lareon::buttons.simple>
            </div>
        </div>
    </fieldset>
</x-lareon::accordion.single>
