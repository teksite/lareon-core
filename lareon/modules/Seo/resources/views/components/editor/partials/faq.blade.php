@props(['name','value' => [],'required' => false,'arrayName'=>'faq' ,'title'=>__('faq')])

@php
    $finalName = $name."[".$arrayName."]";

    $dottedName = str_replace(['[', ']'], ['.', ''], $finalName);
    $rawItems = old($dottedName, $value ?? []) ?? [] ;

    $initialItems = collect($rawItems)->map(function($item){
         return [
                'question' => is_array($item) ? ($item['question'] ?? '') : '',
                'acceptedAnswer' => is_array($item) ? ($item['acceptedAnswer'] ?? ''): '',
            ];
        })
        ->values();

@endphp

<fieldset class="fieldset"
          x-data="{
        items: {{ $initialItems->toJson() }},
        errors: @js($errors->getMessages()),
        addItem() { this.items.push({ question: '',acceptedAnswer: '',}); },
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
                <div class="flex items-center gap-6">
                    <div class="w-full flex flex-col gap-1">
                        <label class="input_label" :for="`faq_question-${index}`" x-text="`{{ __('question') }} #${index + 1}`"></label>
                        <input type="text" @required($required) class="input block w-full" placeholder="write your question"
                               :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.question')}"
                               :name="`{{ $finalName }}[${index}][question]`"
                               :id="`faq_question-${index}`"
                               x-model="item.question">
                        <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.question')" x-text="getError('{{ $dottedName }}.' + index + '.question')"></p>
                    </div>
                    <x-lareon::buttons.simple class="min-w-fit w-fit" size="2xs" color="red" variant="outline" type="button" role="button" title="{{ __('double click to delete') }}" @dblclick="removeItem(index)">
                        &times;
                    </x-lareon::buttons.simple>
                </div>

                <div class="w-full flex flex-col gap-1">
                    <label class="input_label" :for="`faq_acceptedAnswer-${index}`" x-text="`{{ __('accepted answer') }} #${index + 1}`"></label>
                    <textarea @required($required) class="input block w-full" placeholder="write your acceptedAnswer"
                              :class="{'input-error':hasError('{{ $dottedName }}.' + index + '.acceptedAnswer')}"
                              :name="`{{ $finalName }}[${index}][acceptedAnswer]`"
                              :id="`faq_acceptedAnswer-${index}`"
                              x-model="item.acceptedAnswer"></textarea>
                    <p class="message-error" x-show="hasError('{{ $dottedName }}.' + index + '.acceptedAnswer')" x-text="getError('{{ $dottedName }}.' + index + '.acceptedAnswer')"></p>
                </div>
            </div>
        </template>
        <x-lareon::buttons.simple size="xs" color="blue" variant="outline" type="button" role="button" @click="addItem()">
            + {{ __('add') }}
        </x-lareon::buttons.simple>
    </div>
</fieldset>
