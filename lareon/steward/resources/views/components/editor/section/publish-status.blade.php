@props(['instance'=>null ,'wrapperMode'=>'y-box' ] )

@php
    $wrapperClass=match ($wrapperMode){
        'x-box'=>'x-box',
        'y-box'=>'y-box',
        default => null
    };

    $status = old('publish_status', $instance->publish_status ?? null);
    $publishedAt = old('published_status', $instance->published_at ?? null);
@endphp

<section class="{{ $wrapperClass . ' space-y-6'}}" x-data="{publishStatus: '{{ $status }}',published: '{{ \Lareon\Steward\App\Enums\PublishStatusEnum::PUBLISHED->value }}'}">
    <div>
        <x-lareon::inputs.label for="publishStatus_data" :title="__('publish status')" class="mb-3"/>
        <x-lareon::inputs.select id="publishStatus_data" name="publish_status" class="block mt-1 w-full" x-model="publishStatus">
            @foreach(\Lareon\Steward\App\Enums\PublishStatusEnum::cases() as $case)
                <option value="{{ $case->value }}" @selected($status == $case->value)>
                    {{ $case->label() }}
                </option>
            @endforeach
        </x-lareon::inputs.select>

        <x-lareon::inputs.error :messages="$errors->get('publish_status')" class="mt-2"/>
    </div>

    <div x-show="publishStatus == published" x-transition x-cloak>
        <x-lareon::inputs.label for="publishAtInput_data" :title="__('publish date')" class="mb-3"/>
        <x-lareon::inputs.time id="publishAtInput_data" type="datetime-local" name="published_at" :value="old('published_at', $publishedAt)" class="block w-full"/>
        <x-lareon::inputs.error :messages="$errors->get('published_at')" class="mt-2"/>
    </div>
</section>

