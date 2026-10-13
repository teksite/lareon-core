<?php

namespace Lareon\Modules\Questionnaire\App\Service;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lareon\Modules\Questionnaire\App\Models\Form;

class FormFileUploader
{
    protected array $storedPaths = [];

    public function disk(): string
    {
        return config('questionnaire.upload.disk', 'local');
    }

    public function handle(Form $form, array $inputs): array
    {
        if (!$this->containsFile($inputs)) return $inputs;
        if (!$form->has_file) abort(422, 'This form does not accept file uploads.');

        return $this->walk($form, $inputs);
    }

    public function rollback(): void
    {
        if (empty($this->storedPaths)) return;

        Storage::disk($this->disk())->delete($this->storedPaths);
        $this->storedPaths = [];
    }

    protected function walk(Form $form, array $inputs): array
    {
        foreach ($inputs as $key => $value) {
            if ($value instanceof UploadedFile) {
                $inputs[$key] = $this->store($form, $value);
            } elseif (is_array($value)) {
                $inputs[$key] = $this->walk($form, $value);
            }
        }
        return $inputs;
    }

    protected function store(Form $form, UploadedFile $file): array
    {
        $directory = trim(config('questionnaire.upload.directory', 'questionnaire/inboxes'), '/')
            .'/'.$form->getKey()
            .'/'.now()->format('Y/m');

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = Str::uuid()->toString().($extension ? ".{$extension}" : '');

        $path = $file->storeAs($directory, $filename, [
            'disk'       => $this->disk(),
            'visibility' => config('questionnaire.upload.visibility', 'private'),
        ]);

        if ($path === false) throw new \RuntimeException('File upload failed.');

        $this->storedPaths[] = $path;

        return [
            'disk'          => $this->disk(),
            'path'          => $path,
            'original_name' => $file->getClientOriginalName(),
        ];
    }

    protected function containsFile(array $inputs): bool
    {
        foreach (Arr::dot($inputs) as $value) {
            if ($value instanceof UploadedFile) return true;
        }
        return false;
    }
}
