@props([
    'name',
    'title',
    'required' => false,
    'open' => false,
    'accordion' => false,
    'multiple' => false,
    'placeholder' => null,
    'value'=>[],
    'selected' => [],

    'model',
    'dataLabel',
    'dataValue',
    'dataSearch',
])

@pushonce('headerScripts')
    @vite(['lareon/modules/Meta/resources/js/app.js', 'lareon/modules/Meta/resources/css/app.css'])
@endpushonce
@php

    $finalItemsName = $name . '[data]';
    $stringifiedName = arrayToDot($finalItemsName);
    $modelClass = rtrim($model ,'::class');
    if (!class_exists($modelClass)) throw new \InvalidArgumentException("Model [{$modelClass}] does not exist." );
    $selectedValues = filled($selected) ? array_map('strval', (array) $selected) : [];
    $finalId = $attributes->get('id') ?? 'dynamic_select_' . \Illuminate\Support\Str::random(8);

    $items = $modelClass::query()->whereIn((new $modelClass)->getKeyName(), $value)
    ->select([$dataValue, $dataLabel, $dataSearch])
    ->get();

@endphp

<div>
    <x-lareon::accordion.single :title="__($title)" :open="$open" :accordion="$accordion">
        <input type="hidden" name="{{ $name }}[element_id]" value="{{ $elementId }}">

        <div class="flex gap-1 items-stretch">

            <div class="w-full">
                <label class='input_label' for="{{$finalId}}">
                    {{$title}}
                    @if($required)
                        <span class="text-red-600 text-xs font-bold">*</span>
                    @endif
                </label>

                <select
                    id="{{ $finalId }}"
                    name="{{ $multiple ? $finalItemsName . '[]' : $finalItemsName }}"
                    @required($required)
                    {{$multiple === "true" ? 'multiple' : ''}}
                    data-model="{{ $modelClass }}"
                    data-value-field="{{ $dataValue }}"
                    data-label-field="{{ $dataLabel }}"
                    data-search-field="{{ $dataSearch }}"
                    {{ $attributes->merge(['class' => 'dynamic-select']) }}
                >
                    @if($placeholder && !$multiple)
                        <option value="">
                            {{ __($placeholder) }}
                        </option>
                    @endif

                    @foreach($items as $item)
                        @php
                            $itemValue = $item->$dataValue;
                            $itemLabel = $item->$dataLabel;
                            $itemSearch = $item->$dataSearch;
                        @endphp
                        <option
                            value="{{ $itemValue }}"
                            data-search="{{ $itemSearch }}"
                            selected
                        >
                            {{ $itemLabel }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-lareon::accordion.single>


</div>
