@props(['required'=>false , 'path'=>'pages/templates' , 'value'=>null , 'wrapperMode'=>null ] )
@php
    $wrapperClass=match ($wrapperMode){
        'x-box'=>'x-box',
        'y-box'=>'y-box',
        default => null
    };

   $files = collect();
   $templatePath = resource_path("views/{$path}");
   if (\Illuminate\Support\Facades\File::isDirectory($templatePath)) {
       $files = collect(File::files($templatePath))
           ->map(fn ($file) => $file->getFilenameWithoutExtension())
           ->map(fn ($file) => str_replace('.blade', '', $file))
           ->sort()
           ->values();
   }
@endphp


<section class="{{$wrapperClass}}">
    <x-lareon::inputs.label for="template_selector" :title="__('template')" :required="$required" class="mb-3"/>
    <x-lareon::inputs.select id="template_selector" class="block mt-1 w-full" name="template" aria-label="{{__('template selector')}}">
        <option value="" {{old('template', $value) ===null ? 'selected' :'' }}>{{__('default')}}</option>
        @foreach($files as $file)
            <option value="{{$file}}" {{old('template', $value) ===$file ? 'selected' :'' }}>{{$file}}</option>
        @endforeach
    </x-lareon::editor.input-select>
    <x-lareon::inputs.error :messages="$errors->get('template')" class="mt-2"/>
</section>
