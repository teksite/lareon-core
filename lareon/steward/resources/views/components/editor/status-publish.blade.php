@props(['value'=>[], 'placeholder'=>null, 'required'=>false , 'type'=>'text' ,'open'=>false ,'accordion'=>true ,'published_at'=>true] )

    <x-lareon::inputs.select id="publishStatus_data" class="block mt-1 w-full" name="publish_status" aria-label="{{__('publish status')}}">
        @foreach(\Lareon\Steward\App\Enums\PublishStatusEnum::cases() as $case)
            <option value="{{ $case->value }}" {{ (old('publish_status', $value[0]?->value ?? null) == $case->value) ? 'selected' : '' }}>
                {{ __($case->getName()) }}
            </option>
        @endforeach
    </x-lareon::inputs.select>
    <x-lareon::inputs.error :messages="$errors->get('published_status')" class="mt-2"/>
    @if($published_at)
        <div>
            <br>
            <x-lareon::inputs.label for="publishAtInput_data" :title="__('publish date')"/>
            <x-lareon::inputs.time id="publishAtInput_data" type="datetime-local" name="published_at" :value="old('published_at', $value[1] ?? '')" class="block w-full"/>
            <x-lareon::inputs.error :messages="$errors->get('published_at')" class="mt-2"/>
        </div>
    @endif

