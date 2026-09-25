@props(['value' => null,    'template' => null,])
@php
    $templatesPath = config('questionnaire.forms',    resource_path('views/questionnaire/forms'));

    $templates = is_dir($templatesPath)
        ? collect(File::allFiles($templatesPath))
        ->filter(fn ($file,) => str_ends_with($file->getFilename(), '.blade.php'))
            ->map(fn ($file,) => [
                'path' => str($file->getPathname())->replaceLast('.blade.php', '')->remove($templatesPath)->trim('/\\')->replace('\\/' ,'.')->toString(),
                'name' => str($file->getFilename())->beforeLast('.blade.php')->toString(),
            ])->values()->all()
        : [];
@endphp

<x-lareon::editor.input-select :value="$value" name="template" :label="__('template')">
    <option value="">{{__('none')}}</option>
    @foreach($templates as $item)
        <option value="{{ $item['path'] ?? null }}">
            {{ $item['name'] }}
        </option>
    @endforeach
</x-lareon::editor.input-select>
