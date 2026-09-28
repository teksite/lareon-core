@props(['required'=>false , 'value'=>null , 'wrapperMode'=>null , 'path', 'name'=>'template' ] )
@php
    $wrapperClass=match ($wrapperMode){
        'x-box'=>'x-box',
        'y-box'=>'y-box',
        default => null
    };
    $viewPath = resource_path('views\\' . str_replace('.', DIRECTORY_SEPARATOR, $path));
    $viewPath = str_replace('/', DIRECTORY_SEPARATOR, $viewPath);
    $viewsPath = resource_path('views');

        $templates = File::isDirectory($viewPath)
            ? collect(File::allFiles($viewPath))->filter( fn (SplFileInfo $file,) =>  $file->getExtension() === 'php' )
                ->map(function (SplFileInfo $file,) use ($viewsPath) {
                    $relativePath = Str::after($file->getPathname(),$viewsPath . DIRECTORY_SEPARATOR);
                    $viewName = preg_replace('/\.blade\.php$/','',$relativePath);
                    $viewName = str_replace(DIRECTORY_SEPARATOR,'.',$viewName );
                    return [
                        'path' => $viewName,
                        'name' => str_replace('.blade' , '' , $file->getFilenameWithoutExtension()),
                    ];
                })
                ->values()
                ->toArray()
            : [];
@endphp


<section class="{{$wrapperClass}}">
    <x-lareon::editor.input-select :value="$value" :required="$required" :label="__('template')" :name="$name" aria-label="{{__('template selector')}}">
        <option value="">{{__('default')}}</option>
        @foreach($templates as $temp)
            <option value="{{$temp['path']}}">{{$temp['name']}}</option>
        @endforeach
    </x-lareon::editor.input-select>
</section>
