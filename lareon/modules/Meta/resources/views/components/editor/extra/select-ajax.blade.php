@props([
    'name',
    'title',
    'required' => false,
    'open' => false,
    'accordion' => true,
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

    $stringifiedName = arrayToDot($name);

    $modelClass = rtrim($model ,'::class');
    if (!class_exists($modelClass)) throw new \InvalidArgumentException("Model [{$modelClass}] does not exist." );


    $selectedValues = filled($selected) ? array_map('strval', (array) $selected) : [];

    $items = $modelClass::query()->whereIn((new $modelClass)->getKeyName(), $value)->select([$dataValue,    $dataLabel,    $dataSearch,])->get();
@endphp

<div>
    <x-lareon::accordion.single :title="__($title)" :open="$open" :accordion="$accordion">

        <div class="flex gap-1 items-stretch">

            <div class="w-full">

                <select
                    name={{"$name"}} @required($required) multiple="{{$multiple}}" id="{{ $attributes->get('id') ?? 'dynamic_select_' . \Illuminate\Support\Str::random(8) }}"
                    data-value-field="{{ $dataValue }}" data-label-field="{{ $dataLabel }}" data-search-field="{{ $dataSearch }}"
                    {{ $attributes->merge(['class' => 'dynamic-select',]) }}>

                    @if($placeholder && !$multiple)
                        <option value="">
                            {{ __($placeholder) }}
                        </option>
                    @endif
                    @foreach($items as $item)
                        @php
                            $value = data_get($item, $dataValue);
                            $label = data_get($item, $dataLabel);
                            $search = data_get($item, $dataSearch);
                        @endphp
                        <option value="{{ $value }}" data-search="{{ $search }}"@selected(in_array((string) $value, $selectedValues, true))>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-lareon::accordion.single>


</div>
