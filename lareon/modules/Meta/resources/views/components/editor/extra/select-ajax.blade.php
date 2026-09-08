@props(['name','title','required' => false,'open' => false,'accordion' => true,'multiple' => false, 'placeholder' => null,
'selected' => [],
'model', 'dataLabel','dataValue','dataSearch',

])
@php

    $stringifiedName = arrayToDot($name);


    $data = filled($selected)  ? (array) $selected  : [];

    /*
     * Get options directly from the model.
     *
     * Example:
     * User::query()
     *     ->select(['id', 'name'])
     *     ->get();
     */
     $model=rtrim($model , '::class');

    $items = (new $model) ->newQuery()->select([$dataValue, $dataLabel, $dataSearch,])->limit(25);
@endphp

<div>
    <x-lareon::accordion.single :title="__($title)" :open="$open" :accordion="$accordion">
        <div class="flex gap-1 items-stretch">
            <div class="w-full">
                <x-lareon::editor.input-select :name="$name" :required="$required" :multiple="$multiple"
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

                        <option value="{{ $value }}" data-search="{{ $search }}">
                            {{ $label }}
                        </option>
                    @endforeach
                </x-lareon::input.select>

            </div>
        </div>

    </x-lareon::accordion.single>

</div>

@once
    @push('scripts')
        <script>
            import TomSelect from "tom-select";

            document.addEventListener('DOMContentLoaded', () => {

                document.querySelectorAll('.dynamic-select') .forEach((element) => {
                    console.log(element)
                        // Prevent initializing the same element twice
                        if (element.tomselect) {
                            return;
                        }

                        new TomSelect(element, {
                            plugins: {
                                remove_button: {
                                    title: 'Remove this item',
                                },
                            },

                            searchField: [
                                'text',
                                'search',
                            ],

                            create: false,

                            maxOptions: null,

                            closeAfterSelect: {{ $multiple ? 'false' : 'true' }},
                        });

                    });

            });
        </script>
    @endpush
@endonce

