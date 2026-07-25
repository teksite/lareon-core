@props(['value'=>[], 'placeholder'=>null, 'required'=>false , 'type'=>'text' ,'open'=>false ,'accordion'=>true ,'published_at'=>true , 'wrapperMode'=>null ] )

@php
    $wrapperClass=match ($wrapperMode){
        'x-box'=>'x-box',
        'y-box'=>'y-box',
        default => null
    };

        $status = old('publish_status', $value[0]?->value ?? null);

@endphp

<section class="{{ $wrapperClass . ' space-y-6'}}"x-data="{publishStatus: '{{ $status }}',postpone: '{{ \Lareon\Steward\App\Enums\PublishStatusEnum::POSTPONE->value }}'}">
    <div>
        <x-lareon::inputs.label for="publishStatus_data" :title="__('publish status')" class="mb-3"/>
        <x-lareon::inputs.select id="publishStatus_data" name="publish_status" class="block mt-1 w-full" x-model="publishStatus">
            @foreach(\Lareon\Steward\App\Enums\PublishStatusEnum::cases() as $case)
                <option value="{{ $case->value }}"@selected($status == $case->value)>
                    {{ $case->label() }}
                </option>
            @endforeach
        </x-lareon::inputs.select>

        <x-lareon::inputs.error :messages="$errors->get('publish_status')" class="mt-2"/>
    </div>

    @if($published_at)
        <div x-show="publishStatus == postpone" x-transition x-cloak>
            <x-lareon::inputs.label for="publishAtInput_data" :title="__('publish date')" class="mb-3"/>
            <x-lareon::inputs.time id="publishAtInput_data" type="datetime-local" name="published_at" :value="old('published_at', $value[1] ?? '')" class="block w-full"/>
            <x-lareon::inputs.error :messages="$errors->get('published_at')" class="mt-2"/>
        </div>
    @endif
</section>

